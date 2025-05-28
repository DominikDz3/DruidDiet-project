<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalculatorDashboardController extends Controller
{
    
    public function showAllCalculators()
    {
        return view('user.combined-calculators');
    }


    public function calculateWaterNeed(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric|min:1',
            'activity_level' => 'required|numeric|min:1|max:2', // Przykład: 1.0 (niska), 1.2 (umiarkowana), 1.4 (wysoka)
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

        return view('user.combined-calculators', [
            'waterNeeded' => round($waterNeeded / 1000, 2), 
            'waterWeight' => $weight,
            'waterActivityLevel' => $activityLevel,
            'activeCalculator' => 'water',  
        ]);


    }
}