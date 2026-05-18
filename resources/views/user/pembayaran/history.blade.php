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
    </style>
    
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="bg-white rounded-2xl shadow-md p-6">
                <!-- Page Header with Icon and Title - Styled like payment form -->
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-8 h-8 flex items-center justify-center bg-primary-500 text-white rounded-lg shadow-md">
                            <i class="fas fa-history"></i>
                        </div>
                        <h1 class="text-xl font-semibold text-gray-900">Riwayat Pembayaran SPP</h1>
                    </div>
                    <p class="text-sm text-gray-500 ml-10">Lihat status dan riwayat pembayaran SPP Anda</p>
                </div>
                
                <!-- Action Button -->
                <div class="flex justify-end mb-4">
                    <a href="{{ route('pembayaran.create') }}" class="bg-primary-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-600 transition-colors flex items-center gap-2 shadow-md">
                        <i class="fas fa-plus"></i>
                        <span>Bayar SPP</span>
                    </a>
                </div>
                
                <!-- Enhanced Table Styling -->
                <div class="overflow-x-auto">
                    <div class="overflow-hidden rounded-xl border border-gray-200 shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pembayaran</th>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="hidden sm:table-cell px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    <th scope="col" class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pembayaran as $item)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-3 sm:px-6 py-2 sm:py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            @php
                                                $itemNames = [];
                                                foreach($item->pembayaranDetail as $detail) {
                                                    if($detail->spp) {
                                                        $itemNames[] = $detail->spp->nama;
                                                    }
                                                }
                                                foreach($item->angsuranDu as $du) {
                                                    $itemNames[] = ($du->spp->nama ?? 'DU') . ' (Angsuran ke-' . $du->angsuran_ke . ')';
                                                }
                                            @endphp

                                            @if(count($itemNames) > 0)
                                                {{ implode(', ', array_slice($itemNames, 0, 2)) }}
                                                @if(count($itemNames) > 2)
                                                    <span class="text-xs text-gray-500">+{{ count($itemNames) - 2 }} lainnya</span>
                                                @endif
                                            @else
                                                SPP {{ $item->tahun_ajaran }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4">
                                        <div class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d M Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4">
                                        @if($item->status_pembayaran == 'lunas')
                                            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="mr-1 w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                                Sudah Bayar
                                            </span>
                                        @elseif($item->status_pembayaran == 'pending')
                                            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="mr-1 w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                                Menunggu
                                            </span>
                                        @elseif($item->status_pembayaran == 'ditolak')
                                            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <span class="mr-1 w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                                Ditolak
                                            </span>
                                        @elseif($item->status_pembayaran == 'belum_bayar')
                                            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="mr-1 w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                                Belum
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="mr-1 w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                                {{ ucfirst(str_replace('_', ' ', $item->status_pembayaran)) }}
                                            </span>
                                        @endif
                                        
                                        <!-- Mobile-only Keterangan (shown only on small screens) -->
                                        @if($item->keterangan)
                                            <div class="block sm:hidden mt-1 text-xs text-gray-500 line-clamp-1">
                                                {{ $item->keterangan }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="hidden sm:table-cell px-3 sm:px-6 py-2 sm:py-4">
                                        <div class="text-sm text-gray-500 line-clamp-2">
                                            @if(isset($item->keterangan) && !is_null($item->keterangan) && $item->keterangan != '')
                                                {{ $item->keterangan }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                            <!-- Debug info -->
                                            <div class="hidden">
                                                Type: {{ gettype($item->keterangan) }}<br>
                                                Empty: {{ empty($item->keterangan) ? 'yes' : 'no' }}<br>
                                                Null: {{ is_null($item->keterangan) ? 'yes' : 'no' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-right">
                                        <button type="button" onclick="showPaymentDetail({{ $item->pembayaran_id }})" class="inline-flex items-center px-1.5 sm:px-2.5 py-1 sm:py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                            <i class="fas fa-eye mr-1"></i> <span class="hidden sm:inline">Detail</span>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center py-6">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p class="text-gray-500 mb-4 font-medium">Belum ada riwayat pembayaran</p>
                                            <a href="{{ route('pembayaran.create') }}" class="bg-primary-500 text-white px-5 py-2 rounded-lg text-sm hover:bg-primary-600 transition-colors shadow-md flex items-center gap-2">
                                                <i class="fas fa-plus"></i>
                                                <span>Bayar SPP Sekarang</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                @if($pembayaran->hasPages())
                <div class="mt-5">
                    {{ $pembayaran->links() }}
                </div>
                @endif
            </div>
        </div>
    </main>

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
                    <button type="button" onclick="downloadReceipt()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <i class="fas fa-download mr-2"></i> Download Bukti Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                    <a class="nav-item" href="{{ route('pembayaran.create') }}">
                        <div class="p-2 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-md hover:shadow-primary-200">
                            <i class="fa fa-credit-card text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Pembayaran</span>
                    </a>

                    <!-- History -->
                    <a class="nav-item nav-active" href="{{ route('pembayaran.history') }}">
                        <div class="p-2 rounded-full transition-all duration-300 hover:scale-110 hover:shadow-md hover:shadow-primary-200">
                            <i class="fas fa-history text-xl"></i>
                        </div>
                        <span class="text-xs font-medium mt-1">Riwayat</span>
                    </a>

                    <!-- Profile -->
                    <div class="relative" x-data="{ profileMenuOpen: false }">
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
        function showPaymentDetail(paymentId) {
            // Show modal and set payment ID as data attribute
            const modal = document.getElementById('payment-detail-modal');
            modal.classList.remove('hidden');
            modal.setAttribute('data-payment-id', paymentId);
            
            // Fetch payment details
            fetch(`/pembayaran/${paymentId}/detail`)
                .then(response => response.json())
                .then(data => {
                    const detailContent = document.getElementById('payment-detail-content');
                    
                    // Format the payment details
                    let html = `
                        <div class="bg-primary-50 p-3 sm:p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-primary-700 text-sm sm:text-base">Informasi Pembayaran</h4>
                                <span class="text-xs px-2 py-1 rounded-full ${getStatusClass(data.status_pembayaran)}">
                                    ${formatStatus(data.status_pembayaran)}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 sm:gap-3 text-xs sm:text-sm">
                                <div>
                                    <p class="text-gray-500">Tahun Ajaran</p>
                                    <p class="font-medium">${data.tahun_ajaran}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Tanggal Bayar</p>
                                    <p class="font-medium">${formatDate(data.tanggal_bayar)}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Total Tagihan</p>
                                    <p class="font-medium">Rp${formatNumber(data.total_tagihan)}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Total Bayar</p>
                                    <p class="font-medium">Rp${formatNumber(data.total_bayar)}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Add payment details section
                    if (data.pembayaranDetail && data.pembayaranDetail.length > 0) {
                        html += `
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-700 mb-2 text-sm sm:text-base">Detail Pembayaran</h4>
                                <div class="space-y-2">
                        `;
                        
                        data.pembayaranDetail.forEach(detail => {
                            html += `
                                <div class="border border-gray-200 rounded-lg p-2 sm:p-3">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-xs sm:text-sm">${detail.spp ? detail.spp.nama : 'Item Pembayaran'}</span>
                                        <span class="text-primary-600 text-xs sm:text-sm">Rp${formatNumber(detail.biaya)}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Status: ${formatStatus(detail.status_pembayaran)}
                                    </div>
                                </div>
                            `;
                        });
                        
                        html += `
                                </div>
                            </div>
                        `;
                    }

                    if (data.angsuranDu && data.angsuranDu.length > 0) {
                        data.angsuranDu.forEach(du => {
                            const duName = du.spp ? du.spp.nama : `DU - ${data.tahun_ajaran}`;
                            const duAmount = du.nominal_angsuran ?? data.total_tagihan;
                            html += `
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700 mb-2 text-sm sm:text-base">Detail DU</h4>
                                    <div class="space-y-2">
                                        <div class="border border-gray-200 rounded-lg p-2 sm:p-3">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium text-xs sm:text-sm">${duName} - Angsuran ke-${du.angsuran_ke}</span>
                                                <span class="text-primary-600 text-xs sm:text-sm">Rp${formatNumber(duAmount)}</span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                Status: ${formatStatus(du.status)}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                    }
                    
                    // Add bukti bayar section if available
                    if (data.bukti_bayar) {
                        // Ambil nama file saja dari path apapun
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
        
        function downloadReceipt() {
            // Get the payment ID from the current modal
            const paymentId = document.querySelector('#payment-detail-modal').getAttribute('data-payment-id');
            
            if (!paymentId) {
                console.error('Payment ID not found');
                alert('Tidak dapat mengunduh kwitansi: ID pembayaran tidak ditemukan');
                return;
            }
            
            const url = `/pembayaran/${paymentId}/receipt`;
            fetch(url, {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/pdf' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal mengunduh bukti pembayaran');
                }
                return response.blob();
            })
            .then(blob => {
                const objectUrl = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = objectUrl;
                link.download = `kwitansi-pembayaran-${paymentId}.pdf`;
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(objectUrl);
            })
            .catch(() => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Bukti pembayaran tidak dapat diunduh. Silakan coba lagi.',
                    confirmButtonColor: '#16a34a'
                });
            });
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
                    return 'bg-green-100 text-green-800';
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