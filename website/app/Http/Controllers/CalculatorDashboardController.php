<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BmiResult;

class CalculatorDashboardController extends Controller
{
    
    public function showAllCalculators()
    {
        $user = Auth::user();
        $latestBmi = null;

        if ($user) {
            $latestBmi = BmiResult::where('user_id', $user->user_id)
                                ->latest('created_at') 
                                ->first(); 
        }


        return view('user.combined-calculators',['latestBmi' => $latestBmi,]);
    }


    public function calculateWaterNeed(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:1',
            'activity_level' => 'required|numeric|min:1|max:2', 
        ],
        [
            'weight.required' => 'Waga jest wymagana.',
            'weight.numeric' => 'Waga musi być liczbą.',
            'weight.min' => 'Waga musi być większa od 0.',
            'activity_level.required' => 'Poziom aktywności jest wymagany.',
            'activity_level.numeric' => 'Poziom aktywności musi być liczbą.',
            'activity_level.min' => 'Poziom aktywności musi być większy od 0.',
            'activity_level.max' => 'Maksymalny poziom aktywności to 2.',
        ]);

        $weight = $request->input('weight');
        $activityLevel = $request->input('activity_level');
        $waterNeeded = ($weight * 30) * $activityLevel; 

        $user = Auth::user();
        $latestBmi = null;
        if ($user) {
            $latestBmi = BmiResult::where('user_id', $user->id)->latest()->first();
        }

        return view('user.combined-calculators', [
            'waterNeeded' => round($waterNeeded / 1000, 2), 
            'waterWeight' => $weight,
            'waterActivityLevel' => $activityLevel,
            'activeCalculator' => 'water',  
            'latestBmi' => $latestBmi,
        ]);
    }

    public function calculateBmi(Request $request)
    {
        $request->validate([
            'height' => 'required|numeric|min:1',
            'bmi_weight' => 'required|numeric|min:1',
        ],
        [
            // ... (Twoje komunikaty walidacyjne) ...
        ]);

        $height = $request->input('height'); 
        $weight = $request->input('bmi_weight'); 
        $heightInMeters = $height / 100;
        $bmi = $weight / ($heightInMeters * $heightInMeters);

        if (Auth::check()) {
            BmiResult::create([
                'user_id' => Auth::id(),
                'bmi_value' => round($bmi, 2), 
            ]);
        }

        $user = Auth::user();
        $latestBmi = null;
        if ($user) {
            $latestBmi = BmiResult::where('user_id', $user->id)->latest()->first();
        }


        return view('user.combined-calculators', [
            'bmiResult' => round($bmi, 2),
            'bmiHeight' => $height,
            'bmiWeight' => $weight,
            'activeCalculator' => 'bmi',
            'latestBmi' => $latestBmi, 
        ]);
    }
}