<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Catering;

class CateringController extends Controller
{
    public function index(Request $request)
    {
        $query = Catering::query();

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

        $allowedSortColumns = ['title', 'price'];
        $allowedSortDirections = ['asc', 'desc'];

        if (in_array($sortBy, $allowedSortColumns) && in_array($sortDirection, $allowedSortDirections)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            // Jeśli podano nieprawidłowe parametry sortowania, użyj domyślnych
            $query->orderBy('title', 'asc');
        }
    
        // Paginacja
        $caterings = $query->paginate(9); // Zakładamy, że chcemy paginację (ustaw 9 lub inną liczbę)
        
        $cateringTypes = Catering::distinct()->pluck('type')->filter()->sort()->values();
        
        return view('caterings.index', [
            'caterings' => $caterings,
            'cateringTypes' => $cateringTypes,
            // Zmieniamy przekazywanie currentFilters dla sortowania
            'currentFilters' => $request->only(['min_price', 'max_price', 'catering_type', 'sort_option'])
        ]);
    }

    /**
     * Wyświetla szczegóły pojedynczego cateringu.
     *
     * @param \App\Models\Catering $catering
     * @return \Illuminate\View\View
     */
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
            'allergens' => 'nullable|string',
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
        ]);

        $photoPath = $catering->photo; 

        if ($request->hasFile('photo')) {
            if ($catering->photo) {
                Storage::disk('public')->delete($catering->photo);
            }
            
            $photoPath = $request->file('photo')->store('caterings', 'public');
        } else if ($request->input('remove_photo')) { 
                Storage::disk('public')->delete($catering->photo);
                $photoPath = null;
            }
        }

        $catering->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->price,
            'photo' => $photoPath, 
            'elements' => $request->elements,
            'allergens' => $request->allergens,
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
