<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catering;
use App\Models\BmiResult;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CateringController extends Controller
{
    public function index(Request $request)
    {
        $allCateringsQuery = Catering::query();
        $suggestedCaterings = collect();

        $user = Auth::user();
        $latestUserBmiResult = null; 
        $bmiCategory = null;

        if ($user) {
            $latestUserBmiResult = $user->bmiResults()->latest('created_at')->first();
            $sessionKeyForSuggestions = 'suggested_caterings_user_' . $user->user_id;
            $sessionKeyForBmiValue = 'suggested_bmi_value_user_' . $user->user_id;
            $sessionKeyForBmiCategory = 'suggested_bmi_category_user_' . $user->user_id;

            $cachedSuggestions = session($sessionKeyForSuggestions);
            $cachedBmiValue = session($sessionKeyForBmiValue);
            $cachedBmiCategory = session($sessionKeyForBmiCategory);

            if ($latestUserBmiResult && $cachedBmiValue === $latestUserBmiResult->bmi_value && $cachedSuggestions) {
                $suggestedCaterings = $cachedSuggestions;
                $bmiCategory = $cachedBmiCategory;
            } else {
                if ($latestUserBmiResult && $latestUserBmiResult->bmi_value > 0) {
                    $calculatedBmi = $latestUserBmiResult->bmi_value;
                    $minCaloriesForCatering = 0;
                    $maxCaloriesForCatering = 0;

                    if ($calculatedBmi < 18.5) {
                        $minCaloriesForCatering = 2500;
                        $maxCaloriesForCatering = 4000;
                        $bmiCategory = 'Niedowaga';
                    } elseif ($calculatedBmi >= 18.5 && $calculatedBmi < 25) {
                        $minCaloriesForCatering = 1800;
                        $maxCaloriesForCatering = 2500;
                        $bmiCategory = 'Waga prawidłowa';
                    } elseif ($calculatedBmi >= 25 && $calculatedBmi < 30) {
                        $minCaloriesForCatering = 1500;
                        $maxCaloriesForCatering = 2000;
                        $bmiCategory = 'Nadwaga';
                    } else {
                        $minCaloriesForCatering = 1000;
                        $maxCaloriesForCatering = 1800;
                        $bmiCategory = 'Otyłość';
                    }

                    $suggestedCaterings = Catering::whereBetween('kcal_per_person', [$minCaloriesForCatering, $maxCaloriesForCatering])
                                                  ->inRandomOrder()
                                                  ->limit(4)
                                                  ->get();

                    session([
                        $sessionKeyForSuggestions => $suggestedCaterings,
                        $sessionKeyForBmiValue => $calculatedBmi,
                        $sessionKeyForBmiCategory => $bmiCategory,
                    ]);
                }
            }
        }

        if ($request->filled('min_calories')) {
            $allCateringsQuery->where('kcal_per_person', '>=', $request->input('min_calories'));
        }
        if ($request->filled('max_calories')) {
            $allCateringsQuery->where('kcal_per_person', '<=', $request->input('max_calories'));
        }

        if ($request->filled('min_price')) {
            $allCateringsQuery->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $allCateringsQuery->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('catering_type') && $request->input('catering_type') != 'all') {
            $allCateringsQuery->where('type', $request->input('catering_type'));
        }

        $sortOption = $request->input('sort_option', 'title_asc');
        $sortParts = explode('_', $sortOption);
        $sortBy = $sortParts[0] ?? 'title';
        $sortDirection = $sortParts[1] ?? 'asc';

        $allowedSortColumns = ['title', 'price', 'kcal_per_person'];
        $allowedSortDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortColumns) && in_array($sortDirection, $allowedSortDirections)) {
            $allCateringsQuery->orderBy($sortBy, $sortDirection);
        } else {
            $allCateringsQuery->orderBy('title', 'asc');
        }
        
        $allCaterings = $allCateringsQuery->paginate(9)->appends($request->except('page'));

        $cateringTypes = Catering::distinct()->pluck('type')->filter()->sort()->values();

        return view('caterings.index', [
            'suggestedCaterings' => $suggestedCaterings,
            'allCaterings' => $allCaterings,
            'latestUserBmiResult' => $latestUserBmiResult,
            'bmiCategory' => $bmiCategory,
            'cateringTypes' => $cateringTypes,
            'currentFilters' => $request->only(['min_calories', 'max_calories', 'min_price', 'max_price', 'catering_type', 'sort_option']),
        ]);
    }

    public function show(Catering $catering)
    {
        return view('caterings.show', compact('catering'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:50', 
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'elements' => 'nullable|string',
            'allergens' => 'nullable|string',
            'kcal_per_person' => 'nullable|integer|min:0',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('caterings', 'public'); 
        }

        Catering::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->price,
            'photo' => $photoPath, 
            'elements' => $request->elements,
            'allergens' => $request->allergens,
            'kcal_per_person' => $request->kcal_per_person,
        ]);

        return redirect()->route('caterings.index')->with('success', 'Catering został dodany!');
    }

    public function update(Request $request, Catering $catering)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'elements' => 'nullable|string',
            'allergens' => 'nullable|string',
            'kcal_per_person' => 'nullable|integer|min:0',
        ]);

        $photoPath = $catering->photo;

        if ($request->hasFile('photo')) {
            if ($catering->photo) {
                Storage::disk('public')->delete($catering->photo);
            }
            $photoPath = $request->file('photo')->store('caterings', 'public');
        } else if ($request->input('remove_photo')) {
            if ($catering->photo) {
                Storage::disk('public')->delete($catering->photo);
            }
            $photoPath = null;
        } 

        $catering->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->price,
            'photo' => $photoPath,
            'elements' => $request->elements,
            'allergens' => $request->allergens,
            'kcal_per_person' => $request->kcal_per_person,
        ]);

        return redirect()->route('caterings.index')->with('success', 'Catering został zaktualizowany!');
    }

    public function destroy(Catering $catering)
    {
        if ($catering->photo) {
            Storage::disk('public')->delete($catering->photo);
        }
        $catering->delete();
        return redirect()->route('caterings.index')->with('success', 'Catering został usunięty!');
    }
}