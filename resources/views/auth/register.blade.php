<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Siswa - {{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" style="font-family: 'Inter', sans-serif;">
    <div class="min-h-screen flex flex-col md:flex-row items-center justify-center p-4 md:p-0">
        <!-- Left Side -->
        <div class="w-full md:w-1/2 p-6 md:p-12 flex flex-col items-center justify-center md:min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-t-2xl md:rounded-l-2xl md:rounded-tr-none">
            <div class="flex flex-col items-center justify-center">
                <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-80 w-80 mx-auto object-contain mb-6">
                <div class="text-center">
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight">Sistem Pembayaran SPP</h1>
                    <h2 class="text-xl md:text-2xl font-medium">SMK YPT KOTA TEGAL</h2>
                </div>
            </div>
        </div>

        <!-- Right Side -->
        <div class="w-full md:w-1/2 bg-white p-6 md:p-12 rounded-b-2xl md:rounded-r-2xl md:rounded-bl-none shadow-lg overflow-y-auto">
            <div class="max-w-md mx-auto">
                <div class="text-center md:text-left mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Daftar Akun Siswa</h2>
                    <p class="mt-2 text-gray-600">Isi data diri kamu untuk membuat akun</p>
                </div>

                @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong class="font-bold">Error!</strong>
                    <p class="text-sm mt-1">{{ session('error') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong class="font-bold">Error!</strong>
                    <ul class="list-disc pl-5 mt-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm"
                                placeholder="Masukkan nama lengkap">
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <select name="kelas" id="kelas" required
                                class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm">
                                <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>Pilih Kelas</option>
                                <optgroup label="Kelas X">
                                                                        <option value="X RPL 1" {{ old('kelas') == 'X RPL 1' ? 'selected' : '' }}>X RPL 1</option>
                                    <option value="X RPL 2" {{ old('kelas') == 'X RPL 2' ? 'selected' : '' }}>X RPL 2</option>
                                    <option value="X TAV" {{ old('kelas') == 'X TAV' ? 'selected' : '' }}>X TAV</option>
                                    <option value="X TMI 1" {{ old('kelas') == 'X TMI 1' ? 'selected' : '' }}>X TMI 1</option>
                                    <option value="X TMI 2" {{ old('kelas') == 'X TMI 2' ? 'selected' : '' }}>X TMI 2</option>
                                    <option value="X TKR 1" {{ old('kelas') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                                    <option value="X TKR 2" {{ old('kelas') == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                                    <option value="X TKR 3" {{ old('kelas') == 'X TKR 3' ? 'selected' : '' }}>X TKR 3</option>
                                    <option value="X TKR 4" {{ old('kelas') == 'X TKR 4' ? 'selected' : '' }}>X TKR 4</option>
                                    <option value="X TKR 5" {{ old('kelas') == 'X TKR 5' ? 'selected' : '' }}>X TKR 5</option>
                                </optgroup>
                                <optgroup label="Kelas XI">
                                    <option value="XI RPL" {{ old('kelas') == 'XI RPL' ? 'selected' : '' }}>XI RPL</option>
                                    <option value="XI TAV" {{ old('kelas') == 'XI TAV' ? 'selected' : '' }}>XI TAV</option>
                                    <option value="XI TMI 1" {{ old('kelas') == 'XI TMI 1' ? 'selected' : '' }}>XI TMI 1</option>
                                    <option value="XI TMI 2" {{ old('kelas') == 'XI TMI 2' ? 'selected' : '' }}>XI TMI 2</option>
                                    <option value="XI TKR 1" {{ old('kelas') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                                    <option value="XI TKR 2" {{ old('kelas') == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                                    <option value="XI TKR 3" {{ old('kelas') == 'XI TKR 3' ? 'selected' : '' }}>XI TKR 3</option>
                                    <option value="XI TKR 4" {{ old('kelas') == 'XI TKR 4' ? 'selected' : '' }}>XI TKR 4</option>
                                    <option value="XI TKR 5" {{ old('kelas') == 'XI TKR 5' ? 'selected' : '' }}>XI TKR 5</option>
                                </optgroup>
                                <optgroup label="Kelas XII">
                                    <option value="XII RPL" {{ old('kelas') == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
                                    <option value="XII TAV" {{ old('kelas') == 'XII TAV' ? 'selected' : '' }}>XII TAV</option>
                                    <option value="XII TMI 1" {{ old('kelas') == 'XII TMI 1' ? 'selected' : '' }}>XII TMI 1</option>
                                    <option value="XII TMI 2" {{ old('kelas') == 'XII TMI 2' ? 'selected' : '' }}>XII TMI 2</option>
                                    <option value="XII TKR 1" {{ old('kelas') == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                                    <option value="XII TKR 2" {{ old('kelas') == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                                    <option value="XII TKR 3" {{ old('kelas') == 'XII TKR 3' ? 'selected' : '' }}>XII TKR 3</option>
                                    <option value="XII TKR 4" {{ old('kelas') == 'XII TKR 4' ? 'selected' : '' }}>XII TKR 4</option>
                                    <option value="XII TKR 5" {{ old('kelas') == 'XII TKR 5' ? 'selected' : '' }}>XII TKR 5</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal Masuk -->
                    <div>
                        <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            </div>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk') }}" required
                                class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm">
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm"
                                placeholder="Masukkan email aktif">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <input type="password" name="password" id="password" required autocomplete="new-password" minlength="3"
                                class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm"
                                placeholder="Minimal 3 karakter">
                        </div>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" minlength="3"
                                class="block w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm text-sm"
                                placeholder="Ulangi password">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-600 hover:from-blue-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Daftar Sekarang
                        </button>
                    </div>

                    <!-- Divider -->
                    <div class="flex items-center">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="mx-4 text-sm text-gray-400">atau</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <!-- Back to login -->
                    <div>
                        <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border-2 border-blue-600 rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 transition duration-150 ease-in-out">
                            Sudah punya akun? Masuk
                        </a>
                    </div>
                </form>

                <div class="mt-6 text-center text-xs text-gray-500">
                    &copy; {{ date('Y') }} Sistem Pembayaran SPP SMK YPT KOTA TEGAL. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>