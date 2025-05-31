<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $todayCateringTypeName = 'Polecane'; // Domyślna nazwa, jeśli z jakiegoś powodu typ nie zostanie ustalony
        $promotedCaterings = collect(); // Domyślnie pusta kolekcja na wypadek braku typów


        $availableCateringTypes = [
            "Katering impreza", "Katering plenerowy", "Katering biznesowy",
            "Katering wegetariański", "Katering dla dzieci", "Katering śniadaniowy",
            "Katering regionalny", "Katering specjalny", "Katering kulturalny",
            "Katering dla młodzieży", "Katering rodzinny", "Katering okolicznościowy"
        ];
        // Usuwanie duplikatów
        $uniqueTypes = array_values(array_filter(array_unique($availableCateringTypes)));

        if (!empty($uniqueTypes)) {
            $dayOfYear = Carbon::now()->dayOfYear;
            $typeIndex = $dayOfYear % count($uniqueTypes);
            $selectedType = $uniqueTypes[$typeIndex];

            $todayCateringTypeName = $selectedType;


            $promotedCaterings = Catering::where('type', $selectedType)
                                        ->inRandomOrder()
                                        ->take(4)
                                        ->get();
        }

        return view('main', compact('promotedCaterings', 'todayCateringTypeName'));
    }
}
