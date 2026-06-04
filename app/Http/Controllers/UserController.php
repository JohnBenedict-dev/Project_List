<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Handle Updating User Profiles
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'User account ' . $user->name . ' updated successfully!');
    }

    // Handle Deleting Users
    public function destroy(User $user)
    {
        // Prevent removing yourself accidentally
        if (auth()->id() === $user->id) {
            return redirect()->route('dashboard')->with('error', 'Action denied. You cannot remove your current account session.');
        }

        $user->delete();

        return redirect()->route('dashboard')->with('success', 'User account removed from system registry successfully.');
    }
}