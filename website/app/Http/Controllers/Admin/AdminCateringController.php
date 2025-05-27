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
    public function index(Request $request)
    {
        $query = Catering::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('type', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        $query->orderBy('catering_id', 'desc');

        $caterings = $query->paginate(10);
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
            'elements' => 'nullable|string',
            'price' => 'required|numeric|min:0|decimal:0,2',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'allergens' => 'nullable|string',
        ]);


        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = Storage::disk('public_images')->putFile('', $request->file('photo'));
        }

        Catering::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'elements' => $request->elements,
            'price' => $request->price,
            'photo' => $photoPath,
            'allergens' => $request->allergens,
        ]);

        return redirect()->route('admin.caterings.index')->with('success', 'Katering dodano pomyślnie!');
    }

    
    public function show(Catering $catering)
    {
        return redirect()->route('admin.cateringsCRUD.edit', $catering);
    }

    
    public function edit(Catering $catering)
    {
        return view('admin.cateringsCRUD.edit', compact('catering'));
    }

    
    public function update(Request $request, Catering $catering)
    {
        $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string',
            'type' => 'required|string|max:50',
            'elements' => 'nullable|string',
            'price' => 'required|numeric|min:0|decimal:0,2',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'allergens' => 'nullable|string',
            'remove_photo' => 'nullable|boolean',
        ]);

        $photoPath = $catering->photo; 

        if ($request->hasFile('photo')) {
            if ($catering->photo && Storage::disk('public_images')->exists($catering->photo)) {
                Storage::disk('public_images')->delete($catering->photo);
            }
            $photoPath = Storage::disk('public_images')->putFile('', $request->file('photo'));
        } elseif ($request->boolean('remove_photo')) {
            if ($catering->photo && Storage::disk('public_images')->exists($catering->photo)) {
                Storage::disk('public_images')->delete($catering->photo);
            }
            $photoPath = null;
        }

        $catering->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'elements' => $request->elements,
            'price' => $request->price,
            'photo' => $photoPath,
            'allergens' => $request->allergens,
        ]);

        return redirect()->route('admin.caterings.index')->with('success', 'Katering zaktualizowano pomyślnie!');
    
    }

    public function destroy(Catering $catering)
    {
        if ($catering->photo && Storage::disk('public_images')->exists($catering->photo)) {
            Storage::disk('public_images')->delete($catering->photo);
        }
        $catering->delete();
        return redirect()->route('admin.caterings.index')->with('success', 'Katering usunięto pomyślnie!');
    }
}
