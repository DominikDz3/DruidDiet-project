<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diet;
use App\Models\Bmiresult; // Upewnij się, że jest
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
        $shouldSaveBmi = $request->boolean('save_bmi_result'); // Nowy parametr z checkboxa
        $useSavedBmi = $request->boolean('use_saved_bmi'); // Nowy parametr

        if ($user) {
            $latestUserBmiResult = $user->bmiResults()->latest('created_at')->first(); // Pobierz ostatni zapisany wynik dla informacji
        }

        // --- Logika BMI ---
        if ($request->filled('height_bmi') && $request->filled('weight_bmi')) {
            // Obliczanie BMI z danych formularza
            $height = (float) $heightInput;
            $weight = (float) $weightInput;

            if ($height > 0 && $weight > 0) {
                $calculatedBmi = $weight / ($height * $height);

                if ($user && $shouldSaveBmi) {
                    Bmiresult::create([
                        'user_id' => $user->user_id, //
                        'bmi_value' => $calculatedBmi,
                        'created_at' => Carbon::now()
                    ]);
                    // Odśwież $latestUserBmiResult, aby od razu pokazać nowo zapisany
                    $latestUserBmiResult = $user->bmiResults()->latest('created_at')->first();
                }
            }
        } elseif ($user && $useSavedBmi && $latestUserBmiResult) {
            // Użycie ostatniego zapisanego BMI zalogowanego użytkownika do filtrowania
            $calculatedBmi = $latestUserBmiResult->bmi_value;
            // Przypisanie wzrostu i wagi z ostatniego zapisu nie jest możliwe, bo ich nie przechowujemy globalnie
            // $heightInput i $weightInput pozostaną puste lub z poprzedniego żądania (jeśli były)
        }

        // Filtrowanie diet na podstawie aktywnego $calculatedBmi (nowego lub zapisanego)
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
            } else { // Otyłość ($calculatedBmi >= 30)
                $query->where('calories', '<=', 1800);
                $bmiCategory = 'Otyłość';
            }
        }

        // --- Istniejące filtry (działają niezależnie lub dodatkowo, jeśli BMI nie filtruje kalorii) ---
        // Kaloryczność (tylko jeśli nie filtrujemy aktywnie przez BMI)
        if (!($calculatedBmi && $calculatedBmi > 0)) {
             if ($request->filled('min_calories')) {
                $query->where('calories', '>=', $request->input('min_calories'));
            }
            if ($request->filled('max_calories')) {
                $query->where('calories', '<=', $request->input('max_calories'));
            }
        }

        // Cena
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Typ diety
        if ($request->filled('diet_type') && $request->input('diet_type') != 'all') {
            $query->where('type', $request->input('diet_type'));
        }

        // --- Sortowanie ---
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
            'calculatedBmi' => $calculatedBmi, // Może być świeżo obliczone lub z zapisanego rekordu
            'bmiCategory' => $bmiCategory,
            'heightInput' => $heightInput, // Zawsze z formularza lub null
            'weightInput' => $weightInput, // Zawsze z formularza lub null
            'latestUserBmiResult' => $latestUserBmiResult, // Ostatni zapisany wynik zalogowanego użytkownika
        ]);
    }
}