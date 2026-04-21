<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['siswa', 'admin']);
        
        if ($request->has('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('siswa', function($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->name . '%');
                })->orWhereHas('admin', function($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->name . '%');
                });
            });
        }

        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas', $request->kelas);
            });
        }

        // Tampilkan semua user termasuk siswa tidak aktif (lulus)
        $users = $query->get();

        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('admin.user.index', compact('users', 'kelasList'));
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,user_id'
        ]);

        $count = count($request->user_ids);
        User::whereIn('user_id', $request->user_ids)->delete();

        return redirect()->route('admin.users.index')
            ->with('success', $count . ' akun berhasil dihapus!');
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 3 karakter',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama maksimal 255 karakter',
            'nis.required' => 'NIS tidak boleh kosong',
            'nis.string' => 'NIS harus berupa teks',
            'nis.max' => 'NIS maksimal 20 karakter',
            'nis.unique' => 'NIS sudah digunakan',
            'kelas.required' => 'Kelas tidak boleh kosong',
            'kelas.string' => 'Kelas harus berupa teks',
            'kelas.max' => 'Kelas maksimal 10 karakter',
            'tanggal_masuk.required' => 'Tanggal masuk tidak boleh kosong',
            'tanggal_masuk.date' => 'Format tanggal masuk tidak valid',
        ];

        $rules = [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:3'],
            'role' => ['required', 'in:admin,kepsek,siswa'],
            'nama' => ['required', 'string', 'max:255'],
        ];

        if ($request->role === 'siswa') {
            $rules['nis'] = ['required', 'string', 'max:20', 'unique:siswa,nis'];
            $rules['kelas'] = ['required', 'string', 'max:10'];
            $rules['tanggal_masuk'] = ['required', 'date'];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($request->role === 'siswa') {
            Siswa::create([
                'user_id' => $user->user_id,
                'nama' => $request->nama,
                'nis' => $request->nis,
                'kelas' => $request->kelas,
                'tanggal_masuk' => $request->tanggal_masuk,
            ]);
        } else {
            Admin::create([
                'user_id' => $user->user_id,
                'nama' => $request->nama,
                'no_telp' => $request->no_telp ?? null,
                'alamat' => $request->alamat ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $messages = [
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 3 karakter',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama maksimal 255 karakter',
            'nis.required' => 'NIS tidak boleh kosong',
            'nis.string' => 'NIS harus berupa teks',
            'nis.max' => 'NIS maksimal 20 karakter',
            'nis.unique' => 'NIS sudah digunakan',
            'kelas.required' => 'Kelas tidak boleh kosong',
            'kelas.string' => 'Kelas harus berupa teks',
            'kelas.max' => 'Kelas maksimal 10 karakter',
        ];

        $rules = [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'password' => ['nullable', 'string', 'min:3'],
            'role' => ['required', 'in:admin,kepsek,siswa'],
            'nama' => ['required', 'string', 'max:255'],
        ];

        if ($request->role === 'siswa') {
            $rules['nis'] = ['required', 'string', 'max:20', Rule::unique('siswa', 'nis')->ignore($user->siswa->siswa_id ?? null, 'siswa_id')];
            $rules['kelas'] = ['required', 'string', 'max:10'];
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        if ($request->role === 'siswa') {
            if ($user->siswa) {
                $user->siswa->update([
                    'nama' => $request->nama,
                    'nis' => $request->nis,
                    'kelas' => $request->kelas,
                ]);
            } else {
                Siswa::create([
                    'user_id' => $user->user_id,
                    'nama' => $request->nama,
                    'nis' => $request->nis,
                    'kelas' => $request->kelas,
                ]);
                
                if ($user->admin) {
                    $user->admin->delete();
                }
            }
        } else {
            if ($user->admin) {
                $user->admin->update([
                    'nama' => $request->nama,
                    'no_telp' => $request->no_telp ?? null,
                    'alamat' => $request->alamat ?? null,
                ]);
            } else {
                Admin::create([
                    'user_id' => $user->user_id,
                    'nama' => $request->nama,
                    'no_telp' => $request->no_telp ?? null,
                    'alamat' => $request->alamat ?? null,
                ]);
                
                if ($user->siswa) {
                    $user->siswa->delete();
                }
            }
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Akun berhasil dihapus!');
    }
}