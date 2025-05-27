<?php

namespace App\Http\Controllers\Admin;

use App\Models\Catering;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCateringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $caterings = Catering::paginate(10); 
        return view('admin.cateringsCRUD.index', compact('caterings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cateringsCRUD.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'type' => 'required|string|max:50',
            'elements' => 'nullable|text',
            'price' => 'required|numeric|min:0|decimal:0,2',
            'photo' => 'nullable|string|max:255', // Oczekujemy ścieżki tekstowej, np. 'img/catering1.jpg'
            'allergens' => 'nullable|text',
        ]);

        Catering::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'elements' => $request->elements,
            'price' => $request->price,
            'photo' => $request->photo, // Po prostu zapisz podaną ścieżkę
            'allergens' => $request->allergens,
        ]);

        return redirect()->route('admin.cateringsCRUD.index')->with('success', 'Katering dodano pomyślnie!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Catering  $catering
     * @return \Illuminate\Http\Response
     */
    public function show(Catering $catering)
    {
        return redirect()->route('admin.cateringsCRUD.edit', $catering);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Catering  $catering
     * @return \Illuminate\Http\Response
     */
    public function edit(Catering $catering)
    {
        return view('admin.cateringsCRUD.edit', compact('catering'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Catering  $catering
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Catering $catering)
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'type' => 'required|string|max:50',
            'elements' => 'nullable|text',
            'price' => 'required|numeric|min:0|decimal:0,2',
            'photo' => 'nullable|string|max:255', // Nadal oczekujemy ścieżki tekstowej
            'allergens' => 'nullable|text',
        ]);

        $catering->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'elements' => $request->elements,
            'price' => $request->price,
            'photo' => $request->photo, // Zapisz podaną ścieżkę
            'allergens' => $request->allergens,
        ]);

        return redirect()->route('admin.cateringsCRUD.index')->with('success', 'Katering zaktualizowano pomyślnie!');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Catering  $catering
     * @return \Illuminate\Http\Response
     */
    public function destroy(Catering $catering)
    {
        $catering->delete();
        return redirect()->route('admin.cateringsCRUD.index')->with('success', 'Katering usunięto pomyślnie!');
    }
}
