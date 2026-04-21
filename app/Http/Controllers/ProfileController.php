<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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
        $validated = $request->validated();
        
        // Update user email
        $request->user()->fill([
            'email' => $validated['email'] ?? $request->user()->email,
        ]);

        $request->user()->save();
        
        // Handle admin-specific fields
        if ($request->user()->role == 'admin' || $request->user()->role == 'kepsek') {
            if ($request->user()->admin) {
                $adminData = [];
                
                if (isset($validated['nama'])) {
                    $adminData['nama'] = $validated['nama'];
                }
                
                if (isset($validated['no_telp'])) {
                    $adminData['no_telp'] = $validated['no_telp'];
                }
                
                if (isset($validated['alamat'])) {
                    $adminData['alamat'] = $validated['alamat'];
                }
                
                if (!empty($adminData)) {
                    $request->user()->admin->update($adminData);
                }
            }
        }
        
        // Handle siswa-specific fields
        if ($request->user()->role == 'siswa') {
            if ($request->user()->siswa) {
                $siswaData = [];
                
                if (isset($validated['nama'])) {
                    $siswaData['nama'] = $validated['nama'];
                }
                
                if (isset($validated['nis'])) {
                    $siswaData['nis'] = $validated['nis'];
                }
                
                if (!empty($siswaData)) {
                    $request->user()->siswa->update($siswaData);
                }
            }
        }

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