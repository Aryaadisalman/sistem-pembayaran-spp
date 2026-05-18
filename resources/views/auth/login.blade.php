<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50" style="font-family: 'Inter', sans-serif;">
    <div class="min-h-screen flex flex-col md:flex-row items-center justify-center p-4 md:p-0">
        <!-- Left Side - Logo and App Info -->
        <div class="w-full md:w-1/2 p-6 md:p-12 flex flex-col items-center justify-center md:min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-t-2xl md:rounded-l-2xl md:rounded-tr-none">
            <div class="flex flex-col items-center md:items-center justify-center">
                <img src="{{ asset('logo/logo.png') }}" alt="Logo" class="h-80 w-80 mx-auto object-contain mb-6">
                <div class="text-center">
                    <h1 class="text-2xl md:text-3xl font-bold leading-tight">
                        Sistem Pembayaran SPP
                    </h1>
                    <h2 class="text-xl md:text-2xl font-medium">
                        SMK YPT TEGAL
                    </h2>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full md:w-1/2 bg-white p-6 md:p-12 rounded-b-2xl md:rounded-r-2xl md:rounded-bl-none shadow-lg">
            <div class="max-w-md mx-auto">
                <div class="text-center md:text-left mb-8">
                    <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
                    <p class="mt-2 text-gray-600">Silahkan masuk untuk melanjutkan</p>
                </div>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />
                
                <form method="POST" action="{{ route('login') }}" class="space-y-6" novalidate>
                    @csrf
                    
                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                            </div>
                            <x-text-input id="email" class="block mt-1 w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm" type="text" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
            
                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                            <x-text-input id="password" class="block mt-1 w-full pl-10 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                type="password"
                                name="password"
                                required autocomplete="current-password"
                                placeholder="Masukkan password Anda" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
            
                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">
                                {{ __('Lupa password?') }}
                            </a>
                        @endif
                    </div>
            
                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-600 hover:from-blue-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            {{ __('Masuk') }}
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-6 flex items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="mx-4 text-sm text-gray-400">atau</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>

                <!-- Daftar Button -->
                <div class="mt-4">
                    <a href="{{ route('register') }}" class="w-full flex justify-center py-3 px-4 border-2 border-blue-600 rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Daftar Akun Siswa
                    </a>
                </div>

                <!-- Copyright footer -->
                <div class="mt-8 text-center text-xs text-gray-500">
                    &copy; {{ date('Y') }} Sistem Pembayaran SPP SMK YPT TEGAL. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>