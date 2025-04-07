<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use App\Enums\UserRole;
use App\Models\Salutation;
use App\Models\StatusUser;

class UserController extends Controller
{
    public function index()
    {

        if (auth()->user()->role !== UserRole::ADMIN) {
            abort(403);
        }

        $users = User::select('id', 'name', 'email', 'role', 'status_user_id', 'salutation_id')
            ->with(['statusUser', 'salutation'])
            ->orderBy('name')
            ->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function edit(User $user)
    {
        $salutations = Salutation::all();
        $statuses = StatusUser::all();

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'salutations' => $salutations,
            'statuses' => $statuses,
        ]);
    }

    public function update(Request $request, User $user)
    {

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'salutation_id' => ['required', 'exists:salutations,id'],
            'status_user_id' => ['required', 'exists:status_users,id'],
            'role' => ['required', 'in:user,admin'],
        ]);


        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function create()
    {
        return Inertia::render('Users/Create', [
            'salutations' => Salutation::select('id', 'salutation_value')->get(),
            'statuses' => StatusUser::select('id', 'status_value')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'salutation_id' => ['required', 'exists:salutations,id'],
            'status_user_id' => ['required', 'exists:status_users,id'],
            'role' => ['required', 'in:user,admin'],
            'password' => ['required', 'string'], // pas de règle trop forte ici
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'salutation_id' => $validated['salutation_id'],
            'status_user_id' => $validated['status_user_id'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    public function destroy(User $user)
    {
        // Supprimer l'utilisateur
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
