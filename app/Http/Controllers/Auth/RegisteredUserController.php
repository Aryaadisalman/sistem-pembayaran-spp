<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'           => ['required', 'string', 'max:255'],
            'kelas'          => ['required', 'string', 'in:X RPL 1,X RPL 2,X TAV,X TMI 1,X TMI 2,X TKR 1,X TKR 2,X TKR 3,X TKR 4,X TKR 5,XI RPL,XI TAV,XI TMI 1,XI TMI 2,XI TKR 1,XI TKR 2,XI TKR 3,XI TKR 4,XI TKR 5,XII RPL,XII TAV,XII TMI 1,XII TMI 2,XII TKR 1,XII TKR 2,XII TKR 3,XII TKR 4,XII TKR 5'],
            'tanggal_masuk'  => ['required', 'date'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'       => ['required', 'string', 'min:3', 'confirmed'],
        ]);

        // Cek duplikat nama (warning, tidak memblokir submit)
        $namaExists = \App\Models\Siswa::whereRaw('LOWER(nama) = ?', [strtolower(trim($request->nama))])->exists();
        $warningNama = $namaExists ? 'Nama ini sudah terdaftar. Pastikan ini bukan akun duplikat.' : null;

        DB::beginTransaction();
        try {
            $user = User::create([
                'email'    => $request->email,
                'password' => $request->password,
                'role'     => 'siswa',
            ]);

            Siswa::create([
                'user_id'       => $user->user_id,
                'nama'          => $request->nama,
                'nis'           => 'NIS-' . $user->user_id,
                'kelas'         => $request->kelas,
                'tanggal_masuk' => $request->tanggal_masuk,
                'is_aktif'      => true,
            ]);

            DB::commit();

            event(new Registered($user));
            Auth::login($user);

            if ($warningNama) {
                return redirect()->route('dashboard')->with('warning', $warningNama);
            }

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
}