<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Diet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // --- Logika dla Polecanych Cateringów ---
        $promotedCateringDisplayType = 'Polecane Cateringi'; // Domyślna nazwa dla sekcji cateringów
        $promotedCaterings = collect();

        $availableCateringTypes = [
            "Katering impreza", "Katering plenerowy", "Katering biznesowy",
            "Katering wegetariański", "Katering dla dzieci", "Katering śniadaniowy",
            "Katering regionalny", "Katering specjalny", "Katering kulturalny",
            "Katering dla młodzieży", "Katering rodzinny", "Katering okolicznościowy"
        ];
        $uniqueCateringTypes = array_values(array_filter(array_unique($availableCateringTypes)));

        if (!empty($uniqueCateringTypes)) {
            $dayOfYearForCaterings = Carbon::now()->dayOfYear;
            $cateringTypeIndex = $dayOfYearForCaterings % count($uniqueCateringTypes);
            $selectedCateringType = $uniqueCateringTypes[$cateringTypeIndex];
            $promotedCateringDisplayType = $selectedCateringType; // Aktualizujemy nazwę wyświetlaną

            $promotedCaterings = Catering::where('type', $selectedCateringType)
                                        ->inRandomOrder()
                                        ->take(4)
                                        ->get();
        }

        // --- Logika dla Polecanych Diet ---
        $promotedDietDisplayType = 'Polecane Diety'; // Domyślna nazwa dla sekcji diet
        $promotedDiets = collect();

        $availableDietTypes = [ // Na podstawie DietSeeder.php
            'dieta wegetariańska', 'dieta białkowa', 'dieta ketogeniczna',
            'dieta redukcyjna', 'dieta bezglutenowa', 'dieta wegańska',
            'dieta zbilansowana', 'dieta paleo', 'dieta niskowęglowodanowa',
            'dieta sportowa', 'dieta zdrowotna', 'dieta low fodmap', 'dieta sokowa'
        ];
        $uniqueDietTypes = array_values(array_filter(array_unique($availableDietTypes)));

        if (!empty($uniqueDietTypes)) {
            $dayOfYearForDiets = Carbon::now()->dayOfYear + 1; // Małe przesunięcie dla różnorodności
            $dietTypeIndex = $dayOfYearForDiets % count($uniqueDietTypes);
            $selectedDietType = $uniqueDietTypes[$dietTypeIndex];
            $promotedDietDisplayType = ucfirst($selectedDietType); // Aktualizujemy nazwę wyświetlaną

            $promotedDiets = Diet::where('type', $selectedDietType)
                                ->inRandomOrder()
                                ->take(4)
                                ->get();
        }

        // Przekazanie zmiennych do widoku z bardziej opisowymi nazwami
        return view('main', [
            'promotedCaterings' => $promotedCaterings,
            'promotedCateringDisplayType' => $promotedCateringDisplayType,
            'promotedDiets' => $promotedDiets,
            'promotedDietDisplayType' => $promotedDietDisplayType          
        ]);
    }
}
