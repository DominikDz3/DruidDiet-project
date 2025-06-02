<?php

namespace App\Http\Controllers;

// App\Http\Controllers\Controller; // Już dziedziczy
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BmiResult;
use Carbon\Carbon;

class CalculatorDashboardController extends Controller
{
    public function showAllCalculators(Request $request) // Dodano Request
    {
        $user = Auth::user();
        $latestBmiFromDb = null;

        if ($user) {
            $latestBmiFromDb = BmiResult::where('user_id', $user->user_id)
                                ->latest('created_at')
                                ->first();
        }

        // Pobierz dane o ostatnim obliczeniu BMI z sesji flash (jeśli istnieją)
        $bmiCalculationData = $request->session()->get('bmiCalculationResult');
        // Pobierz dane o ostatnim obliczeniu wody z sesji flash
        $waterCalculationData = $request->session()->get('waterCalculationResult');


        return view('user.combined-calculators', [
            'latestBmiFromDb' => $latestBmiFromDb,
            'bmiCalculation' => $bmiCalculationData, // Wynik ostatniego obliczenia BMI
            'waterCalculation' => $waterCalculationData, // Wynik ostatniego obliczenia wody
            'activeCalculator' => $request->session()->get('activeCalculator', 'water'), // Domyślnie woda lub to co z sesji
        ]);
    }

    public function calculateWaterNeed(Request $request)
    {
        $validated = $request->validate([
            'water_weight' => 'required|numeric|min:1|max:500',
            'water_activity_level' => 'required|numeric|min:1.0|max:2.0', // Poziomy aktywności
        ],[
            'water_weight.required' => 'Waga dla kalkulatora wody jest wymagana.',
            'water_weight.numeric' => 'Waga musi być liczbą.',
            'water_weight.min' => 'Waga musi być większa od 0 kg.',
            'water_weight.max' => 'Waga nie może być większa niż 500 kg.',
            'water_activity_level.required' => 'Poziom aktywności jest wymagany.',
            'water_activity_level.min' => 'Nieprawidłowy poziom aktywności.',
            'water_activity_level.max' => 'Nieprawidłowy poziom aktywności.',
        ]);

        $weight = $validated['water_weight'];
        $activityLevel = $validated['water_activity_level'];
        $waterNeeded = ($weight * 30) * $activityLevel;

        $request->session()->flash('waterCalculationResult', [
            'needed' => round($waterNeeded / 1000, 2),
            'weightInput' => $weight,
            'activityLevelInput' => $activityLevel,
        ]);
        $request->session()->flash('activeCalculator', 'water');

        return redirect()->route('calculators.index')->with('success', 'Obliczono zapotrzebowanie na wodę!');
    }

    public function calculateBmi(Request $request)
    {
        $validated = $request->validate([
            'bmi_height' => 'required|numeric|min:50|max:250', // Wysokość w CM
            'bmi_weight' => 'required|numeric|min:1|max:500',   // Waga w KG
            'save_bmi_result_panel' => 'nullable|boolean',
        ],[
            'bmi_height.required' => 'Wzrost dla kalkulatora BMI jest wymagany.',
            'bmi_height.numeric' => 'Wzrost musi być liczbą (w cm).',
            'bmi_height.min' => 'Minimalny wzrost to 50 cm.',
            'bmi_height.max' => 'Maksymalny wzrost to 250 cm.',
            'bmi_weight.required' => 'Waga dla kalkulatora BMI jest wymagana.',
            'bmi_weight.numeric' => 'Waga musi być liczbą (w kg).',
            'bmi_weight.min' => 'Minimalna waga to 1 kg.',
            'bmi_weight.max' => 'Maksymalna waga to 500 kg.',
        ]);

        $heightCm = $validated['bmi_height'];
        $weightKg = $validated['bmi_weight'];
        $shouldSaveBmiPanel = $request->boolean('save_bmi_result_panel');

        $heightM = $heightCm / 100;
        $bmiValue = 0;
        if ($heightM > 0) {
            $bmiValue = round($weightKg / ($heightM * $heightM), 2);
        }

        $bmiCategory = 'Brak danych';
        $alertClass = 'alert-info';
        if ($bmiValue > 0) {
            if ($bmiValue < 18.5) { $bmiCategory = 'Niedowaga'; $alertClass = 'alert-warning'; }
            elseif ($bmiValue < 25) { $bmiCategory = 'Waga prawidłowa'; $alertClass = 'alert-success'; }
            elseif ($bmiValue < 30) { $bmiCategory = 'Nadwaga'; $alertClass = 'alert-warning'; }
            else { $bmiCategory = 'Otyłość'; $alertClass = 'alert-danger'; }
        }

        $message = $bmiValue > 0 ? 'Obliczono BMI!' : 'Nie udało się obliczyć BMI. Sprawdź dane.';

        if (Auth::check() && $shouldSaveBmiPanel && $bmiValue > 0) {
            BmiResult::updateOrCreate(
                ['user_id' => Auth::id(), 'created_at' => Carbon::now()->toDateString()],
                ['bmi_value' => $bmiValue]
            );
            $message = 'Wynik BMI został obliczony i zapisany!';
        }
        
        $calculationData = [
            'value' => $bmiValue,
            'category' => $bmiCategory,
            'heightInput' => $heightCm,
            'weightInput' => $weightKg,
            'alertClass' => $alertClass,
            'calculation_date' => Carbon::now()
        ];
        
        $request->session()->flash('bmiCalculationResult', $calculationData);
        // Przekażemy też do strony diet, jeśli użytkownik tam przejdzie
        $request->session()->flash('bmiResultDataForDiets', $calculationData); 
        $request->session()->flash('activeCalculator', 'bmi');

        return redirect()->route('calculators.index')->with('success', $message);
    }
}