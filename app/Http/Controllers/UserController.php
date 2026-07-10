<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('member')->latest()->get();

        // Members available for new user creation (no existing user account)
        $availableMembers = Member::whereDoesntHave('user')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('users.index', compact('users', 'availableMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id|unique:users,member_id',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,certificate_manager,website_manager',
        ]);

        $member = Member::findOrFail($request->member_id);

        $email = $request->filled('email') ? $request->email : $request->username . '@system.local';

        User::create([
            'member_id' => $request->member_id,
            'name' => $member->full_name,
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,certificate_manager,website_manager',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email ?: $user->email,
            'role' => $request->role,
        ]);

        return redirect('/users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect('/users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect('/users')->with('success', 'User deleted successfully.');
    }
}
