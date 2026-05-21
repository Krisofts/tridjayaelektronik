<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('pages.users.index', compact('users'));
    }

    public function create()
    {
        $groups = config('auth_group.groups');

        return view('pages.users.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'active' => ['nullable', 'boolean'],
            'groups' => ['nullable', 'array'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'active' => $request->boolean('active'),
        ]);

        // ASSIGN GROUP
        if (!empty($validated['groups'])) {
            $user->syncGroups(...$validated['groups']);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat');
    }

    public function edit(User $user)
    {
        $groups = config('auth_group.groups');
        $userGroups = $user->getGroups();

        return view('pages.users.edit', compact('user', 'groups', 'userGroups'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', "unique:users,email,{$user->id}"],
            'password' => ['nullable', 'min:6'],
            'active' => ['nullable', 'boolean'],
            'groups' => ['nullable', 'array'],
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'active' => $request->boolean('active'),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        // SYNC GROUP (replace semua group lama)
        $user->syncGroups(...($validated['groups'] ?? []));

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // optional: bersihkan group & permission kalau mau
        $user->groups()->delete();
        $user->permissions()->delete();

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}