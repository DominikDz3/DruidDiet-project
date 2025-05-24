<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where('email', 'like', "%{$search}%")
                             ->orWhere('name', 'like', "%{$search}%")
                             ->orWhere('surname', 'like', "%{$search}%");
            })
            ->orderBy('user_id', 'desc')
            ->paginate(10);

        return view('admin.usersCRUD.index', compact('users', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = ['user', 'admin'];
        return view('admin.usersCRUD.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();

        $user = new User();
        $user->name = $validatedData['name'];
        $user->surname = $validatedData['surname'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->role = $validatedData['role'];
        $user->loyalty_points = $validatedData['loyalty_points'] ?? 0;
        $user->allergens = $validatedData['allergens'];

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Użytkownik został pomyślnie utworzony.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('admin.usersCRUD.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = ['user', 'admin'];
        return view('admin.usersCRUD.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();

        $user->name = $validatedData['name'];
        $user->surname = $validatedData['surname'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];
        $user->loyalty_points = $validatedData['loyalty_points'] ?? $user->loyalty_points;
        $user->allergens = $validatedData['allergens'] ?? $user->allergens;

        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Dane użytkownika zostały pomyślnie zaktualizowane.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->user_id) {
            return redirect()->route('admin.users.index')->with('error', 'Nie możesz usunąć własnego konta.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'Użytkownik został pomyślnie usunięty.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.users.index')->with('error', 'Nie można usunąć użytkownika, ponieważ ma powiązane dane (np. zamówienia). Usuń najpierw powiązane rekordy.');
        }
    }
}
