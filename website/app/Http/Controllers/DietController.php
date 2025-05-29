<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diet;
use App\Models\BmiResult; 
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DietController extends Controller
{
    public function index(Request $request)
    {
        $query = Diet::query();
        $calculatedBmi = null;
        $bmiCategory = null;
        $user = Auth::user();
        $latestUserBmiResult = null;

        $heightInput = $request->input('height_bmi');
        $weightInput = $request->input('weight_bmi');
        $shouldSaveBmi = $request->boolean('save_bmi_result'); 
        $useSavedBmi = $request->boolean('use_saved_bmi'); 

        if ($user) {
            $latestUserBmiResult = $user->bmiResults()->latest('created_at')->first(); 
        }

        if ($request->filled('height_bmi') && $request->filled('weight_bmi')) {
            $height = (float) $heightInput;
            $weight = (float) $weightInput;

            if ($height > 0 && $weight > 0) {
                $calculatedBmi = $weight / ($height * $height);

                if ($user && $shouldSaveBmi) {
                    BmiResult::create([
                        'user_id' => $user->user_id, //
                        'bmi_value' => $calculatedBmi,
                        'created_at' => Carbon::now()
                    ]);
                    $latestUserBmiResult = $user->bmiResults()->latest('created_at')->first();
                }
            }
        } elseif ($user && $useSavedBmi && $latestUserBmiResult) {
            $calculatedBmi = $latestUserBmiResult->bmi_value;
        }

        if ($calculatedBmi && $calculatedBmi > 0) {
            if ($calculatedBmi < 18.5) {
                $query->where('calories', '>=', 2500);
                $bmiCategory = 'Niedowaga';
            } elseif ($calculatedBmi >= 18.5 && $calculatedBmi < 25) {
                $query->whereBetween('calories', [1800, 2500]);
                $bmiCategory = 'Waga prawidłowa';
            } elseif ($calculatedBmi >= 25 && $calculatedBmi < 30) {
                $query->where('calories', '<=', 2000);
                $bmiCategory = 'Nadwaga';
            } else { 
                $query->where('calories', '<=', 1800);
                $bmiCategory = 'Otyłość';
            }
        }

        if (!($calculatedBmi && $calculatedBmi > 0)) {
             if ($request->filled('min_calories')) {
                $query->where('calories', '>=', $request->input('min_calories'));
            }
            if ($request->filled('max_calories')) {
                $query->where('calories', '<=', $request->input('max_calories'));
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->filled('diet_type') && $request->input('diet_type') != 'all') {
            $query->where('type', $request->input('diet_type'));
        }

        $sortOption = $request->input('sort_option', 'title_asc');
        $sortParts = explode('_', $sortOption);
        $sortBy = $sortParts[0] ?? 'title';
        $sortDirection = $sortParts[1] ?? 'asc';
        $allowedSortColumns = ['title', 'price', 'calories'];
        $allowedSortDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortColumns) && in_array($sortDirection, $allowedSortDirections)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('title', 'asc');
        }

        $diets = $query->paginate(9)->appends($request->except('page'));

        $dietTypes = Diet::distinct()->pluck('type')->filter()->sort()->values();

        return view('diets', [
            'diets' => $diets,
            'dietTypes' => $dietTypes,
            'currentFilters' => $request->only(['min_calories', 'max_calories', 'min_price', 'max_price', 'diet_type', 'sort_option', 'height_bmi', 'weight_bmi', 'save_bmi_result', 'use_saved_bmi']),
            'calculatedBmi' => $calculatedBmi, 
            'bmiCategory' => $bmiCategory,
            'heightInput' => $heightInput, 
            'weightInput' => $weightInput, 
            'latestUserBmiResult' => $latestUserBmiResult, 
        ]);
    }
}