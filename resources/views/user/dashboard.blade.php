<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    


    <style>
        /* Primary color palette */
        :root {
            --primary-50: #f0fdf4;
            --primary-100: #dcfce7;
            --primary-200: #bbf7d0;
            --primary-300: #86efac;
            --primary-400: #4ade80;
            --primary-500: #22c55e;
            --primary-600: #16a34a;
            --primary-700: #15803d;
            --primary-800: #166534;
            --primary-900: #14532d;
            --primary-950: #052e16;
        }
        
        /* Add Tailwind classes equivalents for custom primary colors */
        .bg-primary-50 { background-color: var(--primary-50); }
        .bg-primary-100 { background-color: var(--primary-100); }
        .bg-primary-200 { background-color: var(--primary-200); }
        .bg-primary-300 { background-color: var(--primary-300); }
        .bg-primary-400 { background-color: var(--primary-400); }
        .bg-primary-500 { background-color: var(--primary-500); }
        .bg-primary-600 { background-color: var(--primary-600); }
        .text-primary-400 { color: var(--primary-400); }
        .text-primary-500 { color: var(--primary-500); }
        .text-primary-600 { color: var(--primary-600); }
        .hover\:bg-primary-50:hover { background-color: var(--primary-50); }
        .hover\:bg-primary-100:hover { background-color: var(--primary-100); }
        .hover\:bg-primary-200:hover { background-color: var(--primary-200); }
        .hover\:text-primary-600:hover { color: var(--primary-600); }
        .from-primary-500 { --tw-gradient-from: var(--primary-500); }
        .to-primary-600 { --tw-gradient-to: var(--primary-600); }
        .shadow-primary-200 { --tw-shadow-color: var(--primary-200); }
        .shadow-primary-300\/50 { --tw-shadow-color: rgba(134, 239, 172, 0.5); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }
        ::-webkit-scrollbar-track {
            background: #f9fafb;
        }
        ::-webkit-scrollbar-thumb {
            background: #d1fae5;
            border-radius: 20px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #10b981;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0px); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .animate-fadeIn { animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        .animate-fadeOut { animation: fadeOut 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-pulse { animation: pulse 2s infinite; }
        .animate-shimmer { 
            background: linear-gradient(90deg, rgba(242, 249, 245, 0), rgba(220, 252, 231, 0.6), rgba(242, 249, 245, 0));
            background-size: 200% 100%;
            animation: shimmer 2.5s infinite;
        }
        
        /* Navigation Styles */
        .nav-item {
            @apply flex flex-col items-center justify-center text-gray-500 hover:text-primary-600 transition-all duration-300 relative;
            width: 64px;
        }
        .nav-active {
            @apply text-primary-600;
        }
        .nav-active div {
            @apply bg-primary-50 shadow-md shadow-primary-200;
            animation: pulse 2s infinite;
        }
        .nav-active::after {
            content: '';
            @apply absolute -bottom-2 left-1/2 transform -translate-x-1/2 h-1 w-8 bg-primary-500 rounded-full;
        }
        .menu-item {
            @apply flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-primary-50 hover:text-primary-600 rounded-lg transition-all duration-200;
        }
        .group-logout:hover {
            @apply bg-red-50 text-red-600;
        }
        
        /* Card Styles */
        .card {
            @apply bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-300;
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
        .card-highlight {
            @apply relative overflow-hidden;
        }
        .card-highlight::before {
            content: '';
            @apply absolute top-0 left-0 w-full h-1 bg-gradient-to-r;
        }
        .card-highlight.red::before { @apply from-red-400 to-red-500; }
        .card-highlight.green::before { @apply from-primary-400 to-primary-600; }
        .card-highlight.blue::before { @apply from-blue-400 to-blue-500; }
        
        /* Glass Effect */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(34, 197, 94, 0.08);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(7px);
            -webkit-backdrop-filter: blur(7px);
            border: 1px solid rgba(220, 252, 231, 0.7);
            box-shadow: 0 4px 16px rgba(22, 163, 74, 0.06);
        }
        
        @media (max-width: 640px) {
            .nav-item {
                width: auto;
                @apply px-2;
            }
        }
        @media (max-width: 380px) {
            .nav-item span {
                font-size: 0.65rem;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 relative">    
    <!-- Subtle decorative pattern -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden opacity-25">
        <div class="absolute top-0 -right-40 w-80 h-80 rounded-full bg-primary-100"></div>
        <div class="absolute top-1/4 -left-20 w-60 h-60 rounded-full bg-primary-50"></div>
        <div class="absolute bottom-0 left-1/3 w-40 h-40 rounded-full bg-primary-100"></div>
    </div>
    @if(session('error'))
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    @endif

    <!-- Top Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo & Title -->
                <div class="flex items-center">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('logo/icon.png') }}" class="h-9 w-auto object-contain transition-transform hover:scale-105" alt="Logo" />
                        <div class="text-primary-600 font-bold text-xl">Sistem Pembayaran SPP</div>
                    </div>
                </div>
                
                <!-- Right-side menu icon -->
                <div class="flex items-center gap-4">
                    <div class="relative" x-data="{ isOpen: false }">
                        <button @click="isOpen = !isOpen" class="relative p-2 rounded-full bg-primary-50 hover:bg-primary-100 transition-colors">
                            @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-red-500 border border-white"></span>
                            @endif
                            <i class="fas fa-bell text-primary-600 text-sm"></i>
                        </button>
                        
                        <!-- Notification dropdown -->
                        <div x-show="isOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl py-1 z-30 border border-gray-100">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-semibold text-gray-800">Notifikasi Terbaru</p>
                                    @if($notifications->count() > 0)
                                        <span class="text-xs bg-primary-100 text-primary-800 px-2 py-0.5 rounded-full">{{ $notifications->count() }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($notifications->count() > 0)
                                <div class="max-h-60 overflow-y-auto">
                                    @foreach($notifications->take(2) as $notification)
                                        <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                            <div class="flex">
                                                <div class="flex-shrink-0 mr-3">
                                                    <div class="w-8 h-8 rounded-full bg-{{ $notification['color'] }}-100 flex items-center justify-center">
                                                        <i class="fas fa-{{ $notification['icon'] }} text-{{ $notification['color'] }}-500"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex justify-between items-start">
                                                        <p class="text-sm font-medium text-gray-800">{{ $notification['title'] }}</p>
                                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($notification['time'])->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 mt-1">{{ $notification['message'] }}</p>
                                                    <div class="mt-2 flex justify-between items-center">
                                                        <span class="text-xs font-medium text-{{ $notification['color'] }}-600">Rp{{ number_format($notification['amount'], 0, ',', '.') }}</span>
                                                        <button onclick="showPaymentDetail('{{ $notification['id'] }}')" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                                            Lihat Detail
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($notifications->count() > 2)
                                        <div class="px-4 py-2 text-center border-t border-gray-100">
                                            <a href="{{ route('pembayaran.history') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">
                                                Lihat semua riwayat pembayaran
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <!-- Empty notification state -->
                                <div class="py-6 px-4 text-center">
                                    <div class="w-12 h-12 mx-auto bg-primary-50 rounded-full flex items-center justify-center mb-3">
                                        <i class="fas fa-bell-slash text-primary-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500">Tidak ada notifikasi baru</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pb-20 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <!-- Informasi Siswa & Rekening -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fadeIn" style="animation-delay: 0.4s">
                <!-- Informasi Siswa Card -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200/80">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-graduate text-blue-500 mr-3"></i>
                        Informasi Siswa
                    </h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-5 text-sm">
                        <div>
                            <p class="text-gray-500">Nama</p>
                            <p class="font-bold text-gray-700 mt-1">{{ $siswa->nama }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">NIS</p>
                            <p class="font-bold text-gray-700 mt-1">{{ $siswa->nis }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Kelas</p>
                            <p class="font-bold text-gray-700 mt-1">{{ $siswa->kelas ?? 'Belum ada kelas' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Status</p>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-full mt-1 inline-block">{{ Auth::user()->role }}</span>
                        </div>
                    </div>
                </div>
                <!-- Informasi Rekening Card -->
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200/80">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-university text-blue-500 mr-3"></i>
                        Informasi Rekening
                    </h3>
                    <div class="space-y-5 text-sm">
                        <div class="grid grid-cols-2 gap-x-4">
                            <div>
                                <p class="text-gray-500">Bank</p>
                                <p class="font-bold text-gray-700 mt-1">BCA</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Atas Nama</p>
                                <p class="font-bold text-gray-700 mt-1">SMK YPT TEGAL</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-500">Nomor Rekening</p>
                            <div class="flex items-center bg-gray-100 border border-gray-200 rounded-lg p-2.5 mt-1">
                                <p class="font-mono text-gray-800 tracking-wider flex-grow">234324324</p>
                                <button class="ml-3 text-gray-500 hover:text-blue-600 transition-colors" onclick="copyToClipboard('234324324')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Financial Summary -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Tagihan Bulan Ini Card -->
                @if($sudahBayarBulanIni)
                    {{-- Card when paid --}}
                    <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-md overflow-hidden border border-blue-100 relative group hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fadeIn" style="animation-delay: 0.1s">
                        <div class="absolute top-0 right-0 w-24 h-24 -mt-8 -mr-8 bg-blue-100 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="p-6 relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-600 mb-1 flex items-center">
                                        <i class="fas fa-calendar-check mr-2"></i>
                                        Tagihan Bulan Ini
                                    </p>
                                    <p class="text-2xl font-bold text-blue-700">Lunas</p>
                                    <p class="text-xs text-blue-500 mt-2">Pembayaran bulan ini selesai</p>
                                </div>
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md transform group-hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-check text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-500"></div>
                    </div>
                @else
                    {{-- Card when not paid --}}
                    <div class="bg-gradient-to-br from-red-50 to-white rounded-xl shadow-md overflow-hidden border border-red-100 relative group hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fadeIn" style="animation-delay: 0.1s">
                        <div class="absolute top-0 right-0 w-24 h-24 -mt-8 -mr-8 bg-red-100 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                        <div class="p-6 relative z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-red-600 mb-1 flex items-center">
                                        <i class="fas fa-file-invoice mr-2"></i>
                                        Tagihan Bulan Ini
                                    </p>
                                    <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($tagihanBulanIni ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-xs text-red-500 mt-2">Tagihan SPP bulan {{ \Carbon\Carbon::now()->locale('id')->monthName }}</p>
                                </div>
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-md transform group-hover:rotate-12 transition-transform duration-300">
                                    <i class="fas fa-file-invoice text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="h-1 bg-gradient-to-r from-red-400 to-red-500"></div>
                    </div>
                @endif
                
                <!-- Total Bayar Card -->
                <div class="bg-gradient-to-br from-blue-50 to-white rounded-xl shadow-md overflow-hidden border border-blue-100 relative group hover:shadow-lg hover:-translate-y-1 transition-all duration-300 animate-fadeIn" style="animation-delay: 0.2s">
                    <div class="absolute top-0 right-0 w-24 h-24 -mt-8 -mr-8 bg-blue-100 rounded-full opacity-50 group-hover:scale-110 transition-transform duration-500"></div>
                    <div class="p-6 relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-blue-600 mb-1 flex items-center">
                                    <i class="fas fa-money-bill-wave mr-2"></i>
                                    Total Bayar
                                </p>
                                <p class="text-2xl font-bold text-gray-800">Rp{{ number_format($totalBayar ?? 0, 0, ',', '.') }}</p>
                                <p class="text-xs text-blue-500 mt-2">Pembayaran Lunas</p>
                            </div>
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-md transform group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-money-bill-wave text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="h-1 bg-gradient-to-r from-blue-400 to-blue-500"></div>
                </div>
                

            </div>
        </div>
    </main> 

    <!-- Bottom Navigation Bar -->
    <div class="fixed bottom-0 left-0 right-0 z-30">
        <!-- Main navigation container with glass effect -->
        <div class="bg-white/90 backdrop-blur-xl shadow-lg border-t border-primary-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-around h-18 items-center py-2">
                    <!-- Dashboard -->
                    <a class="nav-item nav-active" href="{{ route('dashboard') }}">
                        <div class="p-2 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-md hover:shadow-primary-200">
                            <i class="fas fa-home text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Dashboard</span>
                    </a>

                    <!-- Pembayaran -->
                    <a class="nav-item" href="{{ route('pembayaran.create') }}">
                        <div class="p-2 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-md hover:shadow-primary-200">
                            <i class="fa fa-credit-card text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Pembayaran</span>
                    </a>

                    <!-- History -->
                    <a class="nav-item" href="{{ route('pembayaran.history') }}">
                        <div class="p-2 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-md hover:shadow-primary-200">
                            <i class="fas fa-history text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Riwayat</span>
                    </a>

                    <!-- Profile -->
                    <div class="relative group" x-data="{ profileMenuOpen: false }">
                        <button @click="profileMenuOpen = !profileMenuOpen" class="nav-item focus:outline-none">
                            <div class="p-2 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-md hover:shadow-primary-200">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <span class="text-xs font-medium mt-1">Profile</span>
                        </button>
                        
                        <!-- Dropdown Menu with glass effect -->
                        <div x-show="profileMenuOpen" @click.away="profileMenuOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute -top-36 right-0 sm:right-auto bg-white/95 backdrop-blur-lg border border-gray-100 rounded-2xl shadow-xl p-2 w-48 z-50">
                            <a class="menu-item" href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-cog text-primary-500"></i>
                                <span>Profile</span>
                            </a>
                            <div class="my-1 border-t border-gray-100"></div>
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="menu-item text-left w-full group-logout">
                                    <i class="fas fa-sign-out-alt text-red-500"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Detail Modal -->
    <div id="payment-detail-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full max-w-sm sm:max-w-lg mx-auto">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 flex items-center">
                                <i class="fas fa-receipt text-primary-500 mr-2"></i>
                                Detail Pembayaran
                            </h3>
                            <div class="mt-4 border-t border-gray-200 pt-4">
                                <div id="payment-detail-content" class="space-y-3">
                                    <!-- Content will be loaded here -->
                                    <div class="flex justify-center">
                                        <svg class="animate-spin h-8 w-8 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closePaymentDetailModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                    <button type="button" onclick="printReceipt()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: "Nomor rekening berhasil disalin",
                    showConfirmButton: false,
                    position: 'bottom-end',
                    timer: 1500,
                    timerProgressBar: true,
                    toast: true,
                    background: '#F0FDF4',
                    iconColor: '#22C55E'
                });
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                Swal.fire({
                    icon: "error",
                    title: "Gagal",
                    text: "Tidak dapat menyalin nomor rekening",
                    showConfirmButton: false,
                    position: 'bottom-end',
                    timer: 1500,
                    toast: true
                });
            });
        }
        
        function showPaymentDetail(paymentId) {
            // Show modal
            document.getElementById('payment-detail-modal').classList.remove('hidden');
            
            // Fetch payment details
            fetch(`/pembayaran/${paymentId}/detail`)
                .then(response => response.json())
                .then(data => {
                    const detailContent = document.getElementById('payment-detail-content');
                    
                        html += `
                                </div>
                            </div>
                        `;
                    }
                    
                    // Add bukti bayar section if available
                    if (data.bukti_bayar) {
                        let buktiPath = data.bukti_bayar;
                        buktiPath = buktiPath.replace(/^\//, '');
                        buktiPath = buktiPath.replace(/^storage\/bukti_bayar\//, '');
                        buktiPath = buktiPath.replace(/^bukti_bayar\//, '');
                        const filename = buktiPath.split('/').pop();
                        const buktiUrl = `/bukti-pembayaran/${filename}`;
                        html += `
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700 mb-2 text-sm sm:text-base">Bukti Pembayaran</h4>
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <a href="${buktiUrl}" target="_blank">
                                        <img src="${buktiUrl}" alt="Bukti Pembayaran" class="w-full h-auto"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<p class=\'text-red-500 text-sm p-3\'>Gambar tidak dapat ditampilkan.</p>';">
                                    </a>
                                </div>
                            </div>
                        `;
                    }
                    
                    // Add notes section if available
                    if (data.keterangan) {
                        html += `
                            <div>
                                <h4 class="font-medium text-gray-700 mb-2 text-sm sm:text-base">Keterangan</h4>
                                <div class="bg-gray-50 p-2 sm:p-3 rounded-lg text-xs sm:text-sm">
                                    ${data.keterangan}
                                </div>
                            </div>
                        `;
                    }
                    
                    detailContent.innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('payment-detail-content').innerHTML = `
                        <div class="bg-red-50 p-4 rounded-lg text-red-600">
                            <p class="font-medium">Terjadi kesalahan saat memuat data</p>
                            <p class="text-sm mt-1">Silakan coba lagi nanti</p>
                            <p class="text-xs mt-2 text-gray-500">Detail error: ${error.message}</p>
                        </div>
                    `;
                    console.error('Error fetching payment details:', error);
                });
        }
        
        function closePaymentDetailModal() {
            document.getElementById('payment-detail-modal').classList.add('hidden');
        }
        
        function printReceipt() {
            // Implementation for printing receipt
            window.print();
        }
        
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return new Intl.DateTimeFormat('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            }).format(date);
        }
        
        function formatNumber(number) {
            if (number === null || number === undefined) return '0';
            return new Intl.NumberFormat('id-ID').format(number);
        }
        
        function getStatusClass(status) {
            switch(status) {
                case 'lunas':
                    return 'bg-blue-100 text-blue-800';
                case 'pending':
                    return 'bg-yellow-100 text-yellow-800';
                case 'ditolak':
                    return 'bg-red-100 text-red-800';
                case 'belum_bayar':
                    return 'bg-gray-100 text-gray-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }
        
        function formatStatus(status) {
            if (!status) return '-';
            switch(status) {
                case 'lunas':
                    return 'Lunas';
                case 'pending':
                    return 'Menunggu Konfirmasi';
                case 'ditolak':
                    return 'Ditolak';
                case 'belum_bayar':
                    return 'Belum Bayar';
                default:
                    return status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            }
        }
        
        // Close modal when clicking outside
        document.getElementById('payment-detail-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentDetailModal();
            }
        });
        
        // Add swipe down to close modal on mobile
        let touchStartY = 0;
        let touchEndY = 0;
        
        const modal = document.getElementById('payment-detail-modal');
        
        modal.addEventListener('touchstart', function(e) {
            touchStartY = e.changedTouches[0].screenY;
        }, false);
        
        modal.addEventListener('touchend', function(e) {
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        }, false);
        
        function handleSwipe() {
            if (touchEndY - touchStartY > 100) { // Swipe down
                closePaymentDetailModal();
            }
        }
    </script>
</body>
</html>