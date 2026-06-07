<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->loadCount(['comments', 'reviews']);

        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->nom = $validated['name'];
        $user->courriel = $validated['email'];
        $user->bio = $validated['bio'] ?? null;

        if ($request->boolean('remove_photo')) {
            $this->deleteProfilePhoto($user);
        } elseif ($request->hasFile('photo_profil')) {
            if ($user->photo_profil) {
                Storage::disk('public')->delete($user->photo_profil);
            }

            $user->photo_profil = $request->file('photo_profil')->store('profiles', 'public');
        }

        if ($user->isDirty('courriel')) {
            $user->courriel_verifie_le = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();
        $this->deleteProfilePhoto($user);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-photo-removed');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->photo_profil) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function deleteProfilePhoto($user): void
    {
        if ($user->photo_profil) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        $user->photo_profil = null;
    }
}
