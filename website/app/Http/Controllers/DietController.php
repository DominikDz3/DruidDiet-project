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
        $user = Auth::user();
        $latestUserBmiResult = null;
        $bmiDisplayData = ['value' => null, 'category' => null, 'source' => null, 'date' => null, 'alertClass' => 'alert-info'];
        $suggestedDiets = collect(); // Pusta kolekcja na start dla sugerowanych diet

        $bmiFromSession = $request->session()->get('bmiResultDataForDiets');

        if ($user) {
            $latestUserBmiResult = $user->bmiResults()->latest('created_at')->first();
        }

        // Ustalenie aktywnego BMI do filtrowania sugestii
        $activeBmiValueForSuggestions = null;
        if ($bmiFromSession && isset($bmiFromSession['value']) && $bmiFromSession['value'] > 0) {
            $activeBmiValueForSuggestions = $bmiFromSession['value'];
            $bmiDisplayData['value'] = $bmiFromSession['value'];
            $bmiDisplayData['category'] = $bmiFromSession['category'] ?? $this->getBmiCategory($bmiFromSession['value']);
            $bmiDisplayData['alertClass'] = $bmiFromSession['alertClass'] ?? $this->getBmiAlertClass($bmiFromSession['value']);
            $bmiDisplayData['source'] = 'session';
            $bmiDisplayData['date'] = $bmiFromSession['calculation_date'] ?? Carbon::now();
            // $request->session()->forget('bmiResultDataForDiets'); // Można odkomentować, by użyć tylko raz
        } elseif ($latestUserBmiResult && $latestUserBmiResult->bmi_value > 0) {
            $activeBmiValueForSuggestions = $latestUserBmiResult->bmi_value;
            $bmiDisplayData['value'] = $latestUserBmiResult->bmi_value;
            $bmiDisplayData['category'] = $this->getBmiCategory($latestUserBmiResult->bmi_value);
            $bmiDisplayData['alertClass'] = $this->getBmiAlertClass($latestUserBmiResult->bmi_value);
            $bmiDisplayData['source'] = 'database';
            $bmiDisplayData['date'] = $latestUserBmiResult->created_at;
        }

        // Pobieranie sugerowanych diet, jeśli mamy aktywne BMI
        if ($activeBmiValueForSuggestions) {
            $suggestedQuery = Diet::query();
            if ($activeBmiValueForSuggestions < 18.5) { $suggestedQuery->where('calories', '>=', 2500); }
            elseif ($activeBmiValueForSuggestions < 25) { $suggestedQuery->whereBetween('calories', [1800, 2500]); }
            elseif ($activeBmiValueForSuggestions < 30) { $suggestedQuery->where('calories', '<=', 2000); }
            else { $suggestedQuery->where('calories', '<=', 1800); }
            
            // Możesz chcieć ograniczyć liczbę sugerowanych diet lub dodać losową kolejność
            $suggestedDiets = $suggestedQuery->inRandomOrder()->limit(3)->get(); // Np. 3 losowe sugerowane
        }

        // Zapytanie dla wszystkich diet (z uwzględnieniem filtrów z formularza)
        $allDietsQuery = Diet::query();

        // Standardowe filtrowanie kalorii (dla listy "Wszystkie diety")
        if ($request->filled('min_calories')) {
            $allDietsQuery->where('calories', '>=', $request->input('min_calories'));
        }
        if ($request->filled('max_calories')) {
            $allDietsQuery->where('calories', '<=', $request->input('max_calories'));
        }
        // Pozostałe filtry (cena, typ diety)
        if ($request->filled('min_price')) { $allDietsQuery->where('price', '>=', $request->input('min_price')); }
        if ($request->filled('max_price')) { $allDietsQuery->where('price', '<=', $request->input('max_price')); }
        if ($request->filled('diet_type') && $request->input('diet_type') != 'all') {
            $allDietsQuery->where('type', $request->input('diet_type'));
        }

        // Sortowanie dla listy "Wszystkie diety"
        $sortOption = $request->input('sort_option', 'title_asc');
        [$sortBy, $sortDirection] = explode('_', $sortOption, 2) + ['title', 'asc'];
        $allowedSortColumns = ['title', 'price', 'calories'];
        $allowedSortDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortColumns) && in_array($sortDirection, $allowedSortDirections)) {
            $allDietsQuery->orderBy($sortBy, $sortDirection);
        } else {
            $allDietsQuery->orderBy('title', 'asc');
        }

        $allDiets = $allDietsQuery->paginate(9)->appends($request->except('page'));
        $dietTypes = Diet::distinct()->pluck('type')->filter()->sort()->values();

        return view('diets', [
            'suggestedDiets' => $suggestedDiets, // Nowa zmienna dla sugerowanych diet
            'allDiets' => $allDiets,             // Zmieniono nazwę z 'diets' dla jasności
            'dietTypes' => $dietTypes,
            'currentFilters' => $request->only(['min_calories', 'max_calories', 'min_price', 'max_price', 'diet_type', 'sort_option']),
            'latestUserBmiResult' => $latestUserBmiResult,
            'bmiDisplayData' => $bmiDisplayData,
        ]);
    }

    private function getBmiCategory($bmiValue)
    {
        if (!$bmiValue || $bmiValue <= 0) return 'Brak danych';
        if ($bmiValue < 18.5) return 'Niedowaga';
        if ($bmiValue < 25) return 'Waga prawidłowa';
        if ($bmiValue < 30) return 'Nadwaga';
        return 'Otyłość';
    }

    private function getBmiAlertClass($bmiValue)
    {
        if (!$bmiValue || $bmiValue <= 0) return 'alert-info';
        if ($bmiValue < 18.5) return 'alert-warning';
        if ($bmiValue < 25) return 'alert-success';
        if ($bmiValue < 30) return 'alert-warning';
        return 'alert-danger';
    }
}