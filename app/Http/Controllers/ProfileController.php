<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        // Explicitly fetch via User model so the IDE recognizes the instance methods
        $user = User::find(Auth::id());

        // Validation mapping check rules
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            'password' => 'nullable|string|min:8',
        ]);

        // Process profile image file uploads if available
        if ($request->hasFile('profile_image')) {
            // Delete old profile avatar image from workspace storage folders if it exists
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Save the file cleanly under directory path 'profile_images' inside public directory storage links
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        // Apply textual detail modifications
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // IDE will now comfortably read this without warnings
        $user->save();

        return redirect()->back()->with('success', 'Your profile details and avatar image updated successfully.');
    }
}