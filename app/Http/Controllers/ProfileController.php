<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists and is not the default image
            if ($user->profile_picture && $user->profile_picture !== 'profile_images/user_base.png') {
                Storage::delete('public/' . $user->profile_picture);
            }

            // Store new profile picture
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_images', 'public');
        } else {
            // Use default profile picture if none is uploaded
            if (!$user->profile_picture) {
                $validated['profile_picture'] = 'profile_images/user_base.png';
            }
        }

        // Handle password update
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->fill($validated);

        // Invalidate email verification if email is changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
