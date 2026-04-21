<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-6 border border-gray-200 rounded-xl bg-white mt-14 shadow-sm">

            @php
                // Map current month to SPP ID - define this at the beginning
                $currentMonth = now()->format('m');
                $mappedSppId = [
                    '01' => 1,
                    '02' => 2,
                    '03' => 3,
                    '04' => 4,
                    '05' => 5,
                    '06' => 6,
                    '07' => 7,
                    '08' => 8,
                    '09' => 9,
                    '10' => 10,
                    '11' => 11,
                    '12' => 12
                ][$currentMonth];
                
                // Define month names
                $months = [
                    '01' => 'Januari',
                    '02' => 'Februari',
                    '03' => 'Maret',
                    '04' => 'April',
                    '05' => 'Mei',
                    '06' => 'Juni',
                    '07' => 'Juli',
                    '08' => 'Agustus',
                    '09' => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember',
                ];
                
                $currentMonthName = $months[$currentMonth];
                $currentYear = now()->year;
                
                // Get count of all active students
                $activeStudentsCount = \App\Models\Siswa::where('is_aktif', true)->count();
                
                // Get count of students who HAVE paid for current month's SPP
                $paidStudentsCount = \App\Models\Pembayaran::where('status_pembayaran', 'lunas')
                    ->whereHas('pembayaranDetail', function($query) use ($currentMonthName) {
                        $query->whereHas('spp', function($q) use ($currentMonthName) {
                            $q->where('nama', 'like', "%SPP%")
                              ->where('nama', 'like', "%$currentMonthName%");
                        });
                    })
                    ->distinct('siswa_id')
                    ->count('siswa_id');
                
                // Calculate unpaid as total active students minus paid students
                $unpaidCount = $activeStudentsCount - $paidStudentsCount;
            @endphp

            <div class="p-0 text-gray-900">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- PEMBAYARAN LUNAS -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'lunas']) }}" class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl flex justify-between items-center hover:cursor-pointer hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shadow-md relative overflow-hidden group">
    @else
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl flex justify-between items-center hover:cursor-not-allowed shadow-md relative overflow-hidden" title="Anda Tidak Memiliki Akses">
    @endif
        <div class="z-10">
          <p class="text-sm font-medium text-blue-100 mb-1 uppercase tracking-wider">Pembayaran Bulan INI</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'lunas')->count() }}</p>
        </div>
        <div class="z-10 bg-blue-400 bg-opacity-30 p-3 rounded-full">
          <i class="fas fa-check-circle text-3xl text-white"></i>
        </div>
        <div class="absolute right-0 bottom-0 h-32 w-32 -mb-12 -mr-12 rounded-full bg-blue-400 bg-opacity-20 transform rotate-12 group-hover:rotate-45 transition-transform duration-300"></div>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- MENUNGGU VALIDASI -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'pending']) }}" class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-6 rounded-xl flex justify-between items-center hover:cursor-pointer hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shadow-md relative overflow-hidden group">
    @else
      <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-6 rounded-xl flex justify-between items-center hover:cursor-not-allowed shadow-md relative overflow-hidden" title="Anda Tidak Memiliki Akses">
    @endif
        <div class="z-10">
          <p class="text-sm font-medium text-yellow-100 mb-1 uppercase tracking-wider">Menunggu Validasi</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'pending')->count() }}</p>
        </div>
        <div class="z-10 bg-yellow-300 bg-opacity-30 p-3 rounded-full">
          <i class="fas fa-clock text-3xl text-white"></i>
        </div>
        <div class="absolute right-0 bottom-0 h-32 w-32 -mb-12 -mr-12 rounded-full bg-yellow-300 bg-opacity-20 transform rotate-12 group-hover:rotate-45 transition-transform duration-300"></div>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- PEMBAYARAN DITOLAK -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'ditolak']) }}" class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-xl flex justify-between items-center hover:cursor-pointer hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shadow-md relative overflow-hidden group">
    @else
      <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 rounded-xl flex justify-between items-center hover:cursor-not-allowed shadow-md relative overflow-hidden" title="Anda Tidak Memiliki Akses">
    @endif
        <div class="z-10">
          <p class="text-sm font-medium text-red-100 mb-1 uppercase tracking-wider">Pembayaran Ditolak</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'ditolak')->count() }}</p>
        </div>
        <div class="z-10 bg-red-400 bg-opacity-30 p-3 rounded-full">
          <i class="fas fa-times-circle text-3xl text-white"></i>
        </div>
        <div class="absolute right-0 bottom-0 h-32 w-32 -mb-12 -mr-12 rounded-full bg-red-400 bg-opacity-20 transform rotate-12 group-hover:rotate-45 transition-transform duration-300"></div>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- TOTAL SEMUA SISWA AKTIF -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.siswa.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl flex justify-between items-center hover:cursor-pointer hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shadow-md relative overflow-hidden group">
    @else
      <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-xl flex justify-between items-center hover:cursor-not-allowed shadow-md relative overflow-hidden" title="Anda Tidak Memiliki Akses">
    @endif
        <div class="z-10">
          <p class="text-sm font-medium text-blue-100 mb-1 uppercase tracking-wider">Total Semua Siswa</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Siswa::where('is_aktif', true)->count() }}</p>
        </div>
        <div class="z-10 bg-blue-400 bg-opacity-30 p-3 rounded-full">
          <i class="fas fa-users text-3xl text-white"></i>
        </div>
        <div class="absolute right-0 bottom-0 h-32 w-32 -mb-12 -mr-12 rounded-full bg-blue-400 bg-opacity-20 transform rotate-12 group-hover:rotate-45 transition-transform duration-300"></div>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- SISWA LUNAS -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.laporan.siswa-lunas', ['spp_id' => $mappedSppId]) }}" class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl flex justify-between items-center hover:cursor-pointer hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shadow-md relative overflow-hidden group">
    @else
      <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-xl flex justify-between items-center hover:cursor-not-allowed shadow-md relative overflow-hidden" title="Anda Tidak Memiliki Akses">
    @endif
        <div class="z-10">
          <p class="text-sm font-medium text-purple-100 mb-1 uppercase tracking-wider">Siswa Lunas</p>
          <p class="text-4xl font-bold text-white">{{ $paidStudentsCount }}</p>
        </div>
        <div class="z-10 bg-purple-400 bg-opacity-30 p-3 rounded-full">
          <i class="fas fa-user-check text-3xl text-white"></i>
        </div>
        <div class="absolute right-0 bottom-0 h-32 w-32 -mb-12 -mr-12 rounded-full bg-purple-400 bg-opacity-20 transform rotate-12 group-hover:rotate-45 transition-transform duration-300"></div>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- SISWA MENUNGGAK -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.laporan.siswa-menunggak', ['spp_id' => $mappedSppId]) }}" class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-6 rounded-xl flex justify-between items-center hover:cursor-pointer hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300 shadow-md relative overflow-hidden group">
    @else
      <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-6 rounded-xl flex justify-between items-center hover:cursor-not-allowed shadow-md relative overflow-hidden" title="Anda Tidak Memiliki Akses">
    @endif
        <div class="z-10">
          <p class="text-sm font-medium text-indigo-100 mb-1 uppercase tracking-wider">Siswa Menunggak</p>
          <p class="text-4xl font-bold text-white">{{ $unpaidCount > 0 ? $unpaidCount : 0 }}</p>
        </div>
        <div class="z-10 bg-indigo-400 bg-opacity-30 p-3 rounded-full">
          <i class="fas fa-user-times text-3xl text-white"></i>
        </div>
        <div class="absolute right-0 bottom-0 h-32 w-32 -mb-12 -mr-12 rounded-full bg-indigo-400 bg-opacity-20 transform rotate-12 group-hover:rotate-45 transition-transform duration-300"></div>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

                </div>
            </div>

            <!-- Pie Chart Section -->
            <div class="mt-8 bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 shadow-md overflow-hidden">
                <div class="bg-indigo-500 text-white py-3 px-6">
                    <h3 class="text-lg font-semibold">Status Pembayaran SPP {{ $currentMonthName }} {{ $currentYear }}</h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1 order-2 md:order-1">
                            <div class="flex flex-col gap-4">
                                <!-- Summary Card -->
                                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-all duration-300">
                                    <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center border-b pb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                                        </svg>
                                        Ringkasan Pembayaran
                                    </h4>
                                    <ul class="space-y-3">
                                        <li class="flex justify-between items-center">
                                            <span class="text-gray-600">Total Siswa Aktif:</span>
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $activeStudentsCount }}</span>
                                        </li>
                                        <li class="flex justify-between items-center">
                                            <span class="text-gray-600">Sudah Bayar:</span>
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $paidStudentsCount }}</span>
                                        </li>
                                        <li class="flex justify-between items-center">
                                            <span class="text-gray-600">Belum Bayar:</span>
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ $unpaidCount > 0 ? $unpaidCount : 0 }}</span>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Progress Card -->
                                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 hover:shadow-md transition-all duration-300">
                                    <h4 class="text-md font-semibold text-gray-700 mb-3 flex items-center border-b pb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        Progres Pembayaran
                                    </h4>
                                    @php
                                        $percentagePaid = $activeStudentsCount > 0 ? round(($paidStudentsCount / $activeStudentsCount) * 100) : 0;
                                    @endphp
                                    <div class="mt-2">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium text-indigo-700">{{ $percentagePaid }}% Terbayar</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="h-2.5 rounded-full bg-gradient-to-r from-indigo-400 to-indigo-600" style="width: {{ $percentagePaid }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 flex justify-center items-center order-1 md:order-2">
                            <div class="w-full h-72 relative">
                                <canvas id="paymentChart"></canvas>
                                <div id="chartCenterText" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-center">
                                    <div class="text-4xl font-bold text-indigo-600">{{ $percentagePaid }}%</div>
                                    <div class="text-sm text-gray-500">Terbayar</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @push('scripts')
            <!-- Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            
            <script>
                // Add subtle animation to numbers
                document.addEventListener('DOMContentLoaded', function() {
                    const numbers = document.querySelectorAll('.text-4xl.font-bold');
                    numbers.forEach(num => {
                        const val = parseInt(num.textContent.trim());
                        let count = 0;
                        const duration = 1500; // animation duration in ms
                        const interval = 50; // update interval in ms
                        const increment = Math.ceil(val / (duration / interval));
                        
                        const timer = setInterval(() => {
                            count += increment;
                            if (count >= val) {
                                num.textContent = val;
                                clearInterval(timer);
                            } else {
                                num.textContent = count;
                            }
                        }, interval);
                    });
                    
                    // Initialize payment status pie chart
                    const paidCount = {{ $paidStudentsCount }};
                    const unpaidCount = {{ $unpaidCount > 0 ? $unpaidCount : 0 }};
                    
                    const ctx = document.getElementById('paymentChart').getContext('2d');
                    const paymentChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Sudah Bayar', 'Belum Bayar'],
                            datasets: [{
                                data: [paidCount, unpaidCount],
                                backgroundColor: [
                                    '#34D399', // Green for paid
                                    '#F87171'  // Red for unpaid
                                ],
                                borderColor: [
                                    '#FFFFFF',
                                    '#FFFFFF'
                                ],
                                borderWidth: 2,
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: 'Status Pembayaran SPP {{ $currentMonthName }} {{ $currentYear }}',
                                    padding: {
                                        top: 10,
                                        bottom: 20
                                    },
                                    font: {
                                        size: 14
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.raw || 0;
                                            const total = paidCount + unpaidCount;
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
            @endpush
        </x-app-layout>