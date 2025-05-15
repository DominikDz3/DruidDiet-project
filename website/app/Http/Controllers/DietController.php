<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diet;

class DietController extends Controller
{
    public function index(Request $request)
    {
        $query = Diet::query();

        // Kaloryczność
        if ($request->filled('min_calories')) {
            $query->where('calories', '>=', $request->input('min_calories'));
        }
        if ($request->filled('max_calories')) {
            $query->where('calories', '<=', $request->input('max_calories'));
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
        $sortOption = $request->input('sort_option', 'title_asc'); // Domyślnie sortuj po tytule rosnąco
        $sortParts = explode('_', $sortOption);

        $sortBy = $sortParts[0] ?? 'title'; // Kolumna do sortowania
        $sortDirection = $sortParts[1] ?? 'asc'; // Kierunek sortowania

        $allowedSortColumns = ['title', 'price', 'calories'];
        $allowedSortDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortColumns) && in_array($sortDirection, $allowedSortDirections)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            // Jeśli podano nieprawidłowe parametry sortowania, użyj domyślnych
            $query->orderBy('title', 'asc');
        }

        $diets = $query->paginate(9); // Zakładamy, że chcemy paginację (ustaw 9 lub inną liczbę)

        $dietTypes = Diet::distinct()->pluck('type')->filter()->sort()->values();

        return view('diets', [
            'diets' => $diets,
            'dietTypes' => $dietTypes,
            // Zmieniamy przekazywanie currentFilters dla sortowania
            'currentFilters' => $request->only(['min_calories', 'max_calories', 'min_price', 'max_price', 'diet_type', 'sort_option'])
        ]);
    }
}