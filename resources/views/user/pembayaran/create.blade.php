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
    
    <script>
        // Show success alert if session has success message
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                });
            });
        @endif

        // Show error alert if session has error message
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    showConfirmButton: true,
                    confirmButtonColor: '#16a34a',
                });
            });
        @endif
    </script>

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
        .text-primary-50 { color: var(--primary-50); }
        .text-primary-100 { color: var(--primary-100); }
        .text-primary-200 { color: var(--primary-200); }
        .text-primary-300 { color: var(--primary-300); }
        .text-primary-400 { color: var(--primary-400); }
        .text-primary-500 { color: var(--primary-500); }
        .text-primary-600 { color: var(--primary-600); }
        .text-primary-700 { color: var(--primary-700); }
        .text-primary-800 { color: var(--primary-800); }
        .text-primary-900 { color: var(--primary-900); }
        
        .bg-primary-50 { background-color: var(--primary-50); }
        .bg-primary-100 { background-color: var(--primary-100); }
        .bg-primary-200 { background-color: var(--primary-200); }
        .bg-primary-300 { background-color: var(--primary-300); }
        .bg-primary-400 { background-color: var(--primary-400); }
        .bg-primary-500 { background-color: var(--primary-500); }
        .bg-primary-600 { background-color: var(--primary-600); }
        .bg-primary-700 { background-color: var(--primary-700); }
        .bg-primary-800 { background-color: var(--primary-800); }
        .bg-primary-900 { background-color: var(--primary-900); }
        
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
        .card-highlight.purple::before { @apply from-purple-400 to-purple-500; }
        .card-highlight.orange::before { @apply from-orange-400 to-orange-500; }
        .card-highlight.yellow::before { @apply from-yellow-400 to-yellow-500; }
        
        /* Status badges */
        .badge {
            @apply px-2 py-1 rounded-full text-xs font-medium;
        }
        .badge-pending {
            @apply bg-yellow-100 text-yellow-800;
        }
        .badge-success {
            @apply bg-primary-100 text-primary-800;
        }
        .badge-error {
            @apply bg-red-100 text-red-800;
        }
        
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
                            @if(isset($unreadCount) && $unreadCount > 0)
                                <span class="absolute top-0 right-0 h-2.5 w-2.5 rounded-full bg-red-500 border border-white"></span>
                            @endif
                            <i class="fas fa-bell text-primary-600 text-sm"></i>
                        </button>
                        
                        <!-- Notification dropdown -->
                        <div x-show="isOpen" @click.away="isOpen = false" 
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
                                    @if(isset($notifications) && $notifications->count() > 0)
                                        <span class="text-xs bg-primary-100 text-primary-800 px-2 py-0.5 rounded-full">{{ $notifications->count() }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if(isset($notifications) && $notifications->count() > 0)
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
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 rounded-xl shadow-xl overflow-hidden mb-6">
            <div class="p-6 sm:p-8">
                <div class="flex items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Form Pembayaran SPP</h1>
                        <p class="text-primary-100 mt-1">Lakukan pembayaran SPP dengan mengisi form berikut</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <!-- Payment Form Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Form Pembayaran</h2>
                
                <form id="payment-form" action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <!-- Payment Options -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h3 class="font-medium text-gray-700 mb-3">Pilih Tagihan</h3>
                        
                        <!-- SPP Section -->
                        @php
                            $sppItems = $activeSpps->filter(function($item) {
                                return strpos($item->nama, 'SPP -') === 0 || strpos($item->nama, 'SPP-') === 0;
                            });
                        @endphp
                        
                        @if($sppItems->count() > 0)
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-4 bg-blue-500 rounded-full mr-2"></div>
                                <h4 class="text-sm font-medium text-blue-600">SPP ({{ $sppItems->count() }})</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                                @foreach($sppItems as $spp)
                                <div class="relative">
                                    <input type="checkbox" 
                                        id="spp_{{ $spp->spp_id }}" 
                                        name="spp_ids[]" 
                                        value="{{ $spp->spp_id }}"
                                        data-nominal="{{ $spp->nominal }}"
                                        class="spp-checkbox hidden">
                                    <label for="spp_{{ $spp->spp_id }}" 
                                        class="block border-2 border-gray-200 rounded-lg p-3 cursor-pointer transition-all hover:border-primary-300 hover:bg-primary-50 spp-label">
                                        <div class="font-medium text-gray-800 text-sm">{{ str_replace(['SPP -', 'SPP-'], '', $spp->nama) }}</div>
                                        <div class="text-red-500 text-xs mt-1">Rp{{ number_format($spp->nominal, 0, ',', '.') }}</div>
                                        <div class="absolute top-2 right-2 w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center checkbox-circle">
                                            <div class="hidden w-2 h-2 bg-primary-500 rounded-full checkbox-dot"></div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- PPDB Section -->
                        @php
                            $ppdbItems = $activeSpps->filter(function($item) {
                                if (isset($item->jenis)) return $item->jenis === 'ppdb';
                                return strpos($item->nama, 'PPDB -') === 0 || strpos($item->nama, 'PPDB-') === 0;
                            });
                        @endphp
                        
                        @if($ppdbItems->count() > 0)
                        <div>
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-4 bg-green-500 rounded-full mr-2"></div>
                                <h4 class="text-sm font-medium text-green-600">PPDB ({{ $ppdbItems->count() }})</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                                @foreach($ppdbItems as $spp)
                                <div class="relative">
                                    <input type="checkbox" 
                                        id="ppdb_{{ $spp->spp_id }}" 
                                        name="ppdb_ids[]" 
                                        value="{{ $spp->spp_id }}"
                                        data-nominal="{{ $spp->nominal }}"
                                        class="ppdb-checkbox hidden">
                                    <label for="ppdb_{{ $spp->spp_id }}" 
                                        class="block border-2 border-gray-200 rounded-lg p-3 cursor-pointer transition-all hover:border-primary-300 hover:bg-primary-50 ppdb-label">
                                        <div class="font-medium text-gray-800 text-sm">{{ str_replace(['PPDB -', 'PPDB-'], '', $spp->nama) }}</div>
                                        <div class="text-red-500 text-xs mt-1">Rp{{ number_format($spp->nominal, 0, ',', '.') }}</div>
                                        <div class="absolute top-2 right-2 w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center checkbox-circle">
                                            <div class="hidden w-2 h-2 bg-primary-500 rounded-full checkbox-dot"></div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- DU Section -->
                        @if(isset($duItems) && $duItems->count() > 0)
                        <div class="mt-4">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-4 bg-orange-500 rounded-full mr-2"></div>
                                <h4 class="text-sm font-medium text-orange-600">DU - Daftar Ulang ({{ $duItems->count() }})</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($duItems as $du)
                                <div class="border-2 rounded-lg p-3 {{ $du->sudah_lunas ? 'border-blue-300 bg-blue-50' : ($du->bisa_bayar ? 'border-orange-200 bg-orange-50' : 'border-yellow-200 bg-yellow-50') }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <div class="font-medium text-gray-800 text-sm">{{ $du->nama }}</div>
                                            <div class="text-xs text-gray-500">{{ $du->tahun_ajaran }}</div>
                                        </div>
                                        @if($du->sudah_lunas)
                                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Lunas</span>
                                        @elseif($du->angsuran_pending > 0)
                                            <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">Menunggu Verifikasi</span>
                                        @else
                                            <span class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full">Ke-{{ $du->angsuran_ke }}/{{ $du->max_angsuran }}x</span>
                                        @endif
                                    </div>

                                    <!-- Progress angsuran -->
                                    <div class="mb-2">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>Progress Angsuran</span>
                                            <span>{{ $du->angsuran_lunas }}/{{ $du->max_angsuran }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-orange-500 h-1.5 rounded-full" style="width: {{ $du->max_angsuran > 0 ? ($du->angsuran_lunas / $du->max_angsuran * 100) : 0 }}%"></div>
                                        </div>
                                    </div>

                                    @if($du->bisa_bayar)
                                    <!-- Tombol bayar DU - pakai real checkbox -->
                                    <div class="mt-2">
                                        <div class="text-xs text-gray-500 mb-2">
                                            Angsuran ke-{{ $du->angsuran_ke }} | Nominal: <strong>Rp {{ number_format($du->nominal_angsuran, 0, ',', '.') }}</strong>
                                        </div>
                                        <label for="du_{{ $du->spp_id }}" 
                                               class="flex items-center gap-2 cursor-pointer p-2 rounded-lg border border-orange-200 hover:bg-orange-100 transition-all"
                                               id="du_label_{{ $du->spp_id }}">
                                            <input type="checkbox"
                                                name="du_ids[]"
                                                value="{{ $du->spp_id }}"
                                                class="du-checkbox w-4 h-4 accent-orange-500"
                                                id="du_{{ $du->spp_id }}"
                                                data-nominal="{{ $du->nominal_angsuran }}">
                                            <span class="text-xs text-gray-700">Bayar angsuran ke-{{ $du->angsuran_ke }} (Rp {{ number_format($du->nominal_angsuran, 0, ',', '.') }})</span>
                                        </label>
                                    </div>
                                    @elseif($du->sudah_lunas)
                                    <div class="text-xs text-blue-600 font-semibold mt-1">✓ DU Sudah Lunas</div>
                                    @else
                                    <div class="text-xs text-yellow-600 mt-1">⏳ Menunggu verifikasi angsuran ke-{{ $du->angsuran_ke - 1 }}</div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Other Items Section -->
                        @php
                            $otherItems = $activeSpps->filter(function($item) {
                                // DU tidak ditampilkan di sini - ada menu khusus angsuran DU
                                if (isset($item->jenis) && $item->jenis === 'du') return false;
                                return strpos($item->nama, 'SPP -') !== 0 && 
                                       strpos($item->nama, 'SPP-') !== 0 && 
                                       strpos($item->nama, 'PPDB -') !== 0 && 
                                       strpos($item->nama, 'PPDB-') !== 0 &&
                                       strpos($item->nama, 'DU -') !== 0 &&
                                       strpos($item->nama, 'DU-') !== 0;
                            });
                        @endphp
                        
                        @if($otherItems->count() > 0)
                        <div class="mt-4">
                            <div class="flex items-center mb-2">
                                <div class="w-2 h-4 bg-purple-500 rounded-full mr-2"></div>
                                <h4 class="text-sm font-medium text-purple-600">Lainnya ({{ $otherItems->count() }})</h4>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                                @foreach($otherItems as $spp)
                                <div class="relative">
                                    <input type="checkbox" 
                                        id="spp_{{ $spp->spp_id }}" 
                                        name="spp_ids[]" 
                                        value="{{ $spp->spp_id }}"
                                        data-nominal="{{ $spp->nominal }}"
                                        class="spp-checkbox hidden">
                                    <label for="spp_{{ $spp->spp_id }}" 
                                        class="block border-2 border-gray-200 rounded-lg p-3 cursor-pointer transition-all hover:border-primary-300 hover:bg-primary-50 spp-label">
                                        <div class="font-medium text-gray-800 text-sm">{{ $spp->nama }}</div>
                                        <div class="text-red-500 text-xs mt-1">Rp{{ number_format($spp->nominal, 0, ',', '.') }}</div>
                                        <div class="absolute top-2 right-2 w-4 h-4 rounded-full border-2 border-gray-300 flex items-center justify-center checkbox-circle">
                                            <div class="hidden w-2 h-2 bg-primary-500 rounded-full checkbox-dot"></div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        @if($activeSpps->count() == 0)
                        <div class="text-center py-3">
                            <p class="text-gray-500">Tidak ada tagihan aktif yang tersedia</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Payment Details -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-calculator text-primary-500 mr-2"></i>
                            Detail Pembayaran
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 text-sm">Total Nominal <span class="text-red-500">*</span></span>
                                <span class="font-medium text-gray-800" id="total-nominal">Rp0</span>
                                <!-- Hidden input to store the total_bayar value -->
                                <input type="hidden" name="total_bayar" id="total_bayar_input" value="0">
                            </div>
                            <p class="text-xs text-gray-500 italic">*pastikan nominal sesuai dengan jumlah yang di transfer</p>
                        </div>
                    </div>
                    
                    <!-- Upload Bukti Bayar -->
                    <div class="mb-6">
                        <label for="bukti_bayar" class="block text-md font-medium text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-image text-primary-500 mr-2"></i>
                            Bukti Pembayaran
                        </label>
                        
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center" id="upload-container">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-cloud-upload-alt text-gray-400 text-3xl mb-2"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="bukti_bayar" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none">
                                            <span>Upload bukti pembayaran</span>
                                            <input id="bukti_bayar" name="bukti_bayar" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">PNG, JPG, atau JPEG hingga 2MB</p>
                                </div>
                                <div id="image-preview" class="hidden mt-3">
                                    <img src="#" alt="Preview" class="mx-auto h-32 object-cover rounded-lg">
                                    <button type="button" onclick="removeImage()" class="mt-2 text-xs text-red-500 hover:text-red-700">
                                        <i class="fas fa-times mr-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between sm:justify-end space-x-3 pt-4">
                        <a href="{{ route('pembayaran.history') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-times mr-1 sm:mr-2"></i><span class="sm:inline">Batal</span>
                        </a>
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <i class="fas fa-paper-plane mr-1 sm:mr-2"></i><span class="sm:inline">Kirim Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    </main>

    <!-- Bottom Navigation Bar -->
    {{-- <div class="fixed bottom-0 left-0 right-0 z-30">
        <!-- Curved top shape with gradient -->
        <div class="absolute -top-5 left-0 right-0 h-5 overflow-hidden">
            <div class="w-full h-10 rounded-t-[50%] bg-gradient-to-r from-primary-400 via-primary-500 to-primary-600 opacity-20"></div>
        </div>
        
        <!-- Main navigation container with glass effect -->
        <div class="bg-white/90 backdrop-blur-xl shadow-lg border-t border-primary-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-around h-18 items-center py-2">
                    <!-- Dashboard -->
                    <a class="nav-item" href="{{ route('dashboard') }}">
                        <div class="p-2 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-md hover:shadow-primary-200">
                            <i class="fas fa-home text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Dashboard</span>
                    </a>

                    <!-- Pembayaran -->
                    <a class="nav-item nav-active" href="{{ route('pembayaran.create') }}">
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
                             class="absolute bottom-20 right-0 w-56 bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl py-2 z-30 border border-gray-100">
                            
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'User' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'email@example.com' }}</p>
                            </div>
                            
                            <div class="py-1">
                                <a href="#" class="menu-item">
                                    <i class="fas fa-user-circle text-gray-400"></i>
                                    <span>Profile</span>
                                </a>
                                <a href="#" class="menu-item">
                                    <i class="fas fa-cog text-gray-400"></i>
                                    <span>Settings</span>
                                </a>
                            </div>
                            
                            <div class="py-1 border-t border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="menu-item w-full text-left group-logout">
                                        <i class="fas fa-sign-out-alt text-gray-400 group-hover:text-red-500"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
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

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all checkboxes
            const sppCheckboxes = document.querySelectorAll('.spp-checkbox');
            const ppdbCheckboxes = document.querySelectorAll('.ppdb-checkbox');
            const duCheckboxes = document.querySelectorAll('.du-checkbox');
            const submitButton = document.querySelector('button[type="submit"]');
            
            // Add event listeners to all checkboxes
            sppCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
                
                // Set initial visual state based on checked status
                const label = document.querySelector(`label[for="${checkbox.id}"]`);
                if (checkbox.checked) {
                    label.classList.add('border-primary-500', 'bg-primary-50');
                    label.querySelector('.checkbox-circle').classList.add('border-primary-500');
                    label.querySelector('.checkbox-dot').classList.remove('hidden');
                }
            });
            
            ppdbCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
                
                // Set initial visual state based on checked status
                const label = document.querySelector(`label[for="${checkbox.id}"]`);
                if (checkbox.checked) {
                    label.classList.add('border-primary-500', 'bg-primary-50');
                    label.querySelector('.checkbox-circle').classList.add('border-primary-500');
                    label.querySelector('.checkbox-dot').classList.remove('hidden');
                }
            });

            duCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateTotal);
            });

            // Calculate initial total
            updateTotal();
            
            // Function to update the total amount
            function updateTotal() {
                let total = 0;
                
                // Calculate total from DU
                document.querySelectorAll('.du-checkbox').forEach(function(cb) {
                    if (cb.checked) {
                        total += parseInt(cb.dataset.nominal) || 0;
                    }
                });

                // Calculate total from SPP
                sppCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const nominal = parseInt(checkbox.dataset.nominal) || 0;
                        total += nominal;
                        
                        // Update visual style for selected items
                        const label = document.querySelector(`label[for="${checkbox.id}"]`);
                        label.classList.add('border-primary-500', 'bg-primary-50');
                        label.querySelector('.checkbox-circle').classList.add('border-primary-500');
                        label.querySelector('.checkbox-dot').classList.remove('hidden');
                    } else {
                        // Reset visual style for unselected items
                        const label = document.querySelector(`label[for="${checkbox.id}"]`);
                        label.classList.remove('border-primary-500', 'bg-primary-50');
                        label.querySelector('.checkbox-circle').classList.remove('border-primary-500');
                        label.querySelector('.checkbox-dot').classList.add('hidden');
                    }
                });
                
                // Calculate total from PPDB
                ppdbCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const nominal = parseInt(checkbox.dataset.nominal) || 0;
                        total += nominal;
                        
                        // Update visual style for selected items
                        const label = document.querySelector(`label[for="${checkbox.id}"]`);
                        label.classList.add('border-primary-500', 'bg-primary-50');
                        label.querySelector('.checkbox-circle').classList.add('border-primary-500');
                        label.querySelector('.checkbox-dot').classList.remove('hidden');
                    } else {
                        // Reset visual style for unselected items
                        const label = document.querySelector(`label[for="${checkbox.id}"]`);
                        label.classList.remove('border-primary-500', 'bg-primary-50');
                        label.querySelector('.checkbox-circle').classList.remove('border-primary-500');
                        label.querySelector('.checkbox-dot').classList.add('hidden');
                    }
                });
                
                // Format and display the total
                document.getElementById('total-nominal').textContent = formatRupiah(total);
                
                // Update the hidden input value for form submission
                document.getElementById('total_bayar_input').value = total;
                
                // Enable/disable submit button based on selection
                const hasSelection = total > 0;
                if (submitButton) {
                    submitButton.disabled = !hasSelection;
                    
                    if (hasSelection) {
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            }
            
            // Format number to Rupiah
            function formatRupiah(number) {
                return 'Rp' + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
            
            // Handle image preview
            const buktiInput = document.getElementById('bukti_bayar');
            if (buktiInput) {
                buktiInput.addEventListener('change', function() {
                    previewImage(this);
                });
            }
            
            // Form submission with validation
            const paymentForm = document.getElementById('payment-form');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Check if at least one item is selected
                    const sppSelected = Array.from(sppCheckboxes).some(checkbox => checkbox.checked);
                    const ppdbSelected = Array.from(ppdbCheckboxes).some(checkbox => checkbox.checked);
                    const duSelected = Array.from(duCheckboxes).some(checkbox => checkbox.checked);
                    
                    if (!sppSelected && !ppdbSelected && !duSelected) {
                        Swal.fire({
                            title: 'Pilih Item Pembayaran',
                            text: 'Silakan pilih minimal satu item SPP, PPDB, atau DU untuk dibayar',
                            icon: 'warning',
                            confirmButtonColor: '#4F46E5',
                        });
                        return;
                    }
                    
                    // Check if bukti pembayaran is uploaded
                    const buktiPembayaran = document.getElementById('bukti_bayar');
                    if (!buktiPembayaran.files || !buktiPembayaran.files[0]) {
                        Swal.fire({
                            title: 'Upload Bukti Pembayaran',
                            text: 'Silakan upload bukti pembayaran terlebih dahulu',
                            icon: 'warning',
                            confirmButtonColor: '#4F46E5',
                        });
                        return;
                    }
                    
                    // Update total_bayar value before submission
                    const totalBayarInput = document.getElementById('total_bayar_input');
                    if (totalBayarInput) {
                        // Make sure the value is set correctly
                        let total = 0;
                        
                        // Calculate from SPP
                        sppCheckboxes.forEach(checkbox => {
                            if (checkbox.checked) {
                                total += parseInt(checkbox.dataset.nominal) || 0;
                            }
                        });
                        
                        // Calculate from PPDB
                        ppdbCheckboxes.forEach(checkbox => {
                            if (checkbox.checked) {
                                total += parseInt(checkbox.dataset.nominal) || 0;
                            }
                        });

                        // Calculate from DU
                        document.querySelectorAll('.du-checkbox').forEach(function(cb) {
                            if (cb.checked) {
                                total += parseInt(cb.dataset.nominal) || 0;
                            }
                        });
                        
                        totalBayarInput.value = total;
                    }
                    
                    // Show loading state
                    Swal.fire({
                        title: 'Memproses Pembayaran',
                        text: 'Mohon tunggu sebentar...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit the form
                    this.submit();
                });
            }
        });
        
        // Handle image preview
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const img = preview.querySelector('img');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                    document.getElementById('upload-container').querySelector('.flex-col').classList.add('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Remove selected image
        function removeImage() {
            const input = document.getElementById('bukti_bayar');
            input.value = '';
            
            const preview = document.getElementById('image-preview');
            preview.classList.add('hidden');
            document.getElementById('upload-container').querySelector('.flex-col').classList.remove('hidden');
        }
        
        // SweetAlert for form submission result
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#4F46E5',
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#4F46E5',
            });
        @endif
    </script>
</body>
</html>