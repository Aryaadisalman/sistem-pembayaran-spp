<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => [
                'sometimes', 
                'string',
                'lowercase',
                'email',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value !== auth()->user()->email && User::where('email', $value)->exists()) {
                        $fail('The email has already been taken.');
                    }
                },
            ],
        ];
        
        // Add admin-specific rules if user is an admin
        if (auth()->user()->role == 'admin' || auth()->user()->role == 'kepsek') {
            $rules['nama'] = ['sometimes', 'string', 'max:25'];
            $rules['no_telp'] = ['sometimes', 'string', 'nullable', 'max:12'];
            $rules['alamat'] = ['sometimes', 'string', 'nullable', 'max:255'];
        }
        
        // Add siswa-specific rules
        if (auth()->user()->role == 'siswa') {
            $rules['nama'] = ['sometimes', 'string', 'max:255'];
            $rules['nis'] = [
                'sometimes',
                'string',
                'max:50',
                function ($attribute, $value, $fail) {
                    $siswaId = auth()->user()->siswa->siswa_id ?? null;
                    $exists = \App\Models\Siswa::where('nis', $value)
                        ->where('siswa_id', '!=', $siswaId)
                        ->exists();
                    if ($exists) {
                        $fail('NIS sudah digunakan oleh siswa lain.');
                    }
                },
            ];
        }
        
        return $rules;
    }
}
