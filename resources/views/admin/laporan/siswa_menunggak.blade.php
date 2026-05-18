<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-6 border border-gray-200 rounded-xl bg-white mt-14 shadow-sm">
            <!-- Header with title and actions -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>Laporan Siswa Menunggak
                </h2>
                <button type="button"
                   onclick="downloadPdfFile('{{ route('admin.laporan.siswa-menunggak', array_merge(request()->query(), ['download' => 1])) }}', 'laporan-siswa-menunggak.pdf')"
                   class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-3 rounded-md shadow-sm transition-all duration-200 flex items-center">
                    <i class="fas fa-download text-xs mr-1.5"></i>Download PDF
                </button>
            </div>

            <!-- Compact filter section -->
            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg mb-5 border border-gray-200 dark:border-gray-600">
                <form action="{{ route('admin.laporan.siswa-menunggak') }}" method="get" class="flex flex-wrap gap-2 items-center">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari nama siswa..." 
                               class="text-sm w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               onchange="this.form.submit()">
                    </div>
                    <div class="flex-1 min-w-[120px] max-w-[200px]">
                        <select name="kelas" id="kelas" class="text-sm w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex-1 min-w-[180px]">
                        <select name="spp_id" id="spp_id" class="text-sm w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                            <option value="">Semua Item Tagihan</option>
                            
                            <!-- Group for SPP -->
                            <optgroup label="SPP Bulanan">
                                @foreach($sppList as $spp)
                                    @if(strpos(strtolower($spp->nama), 'spp') !== false)
                                        <option value="{{ $spp->spp_id }}" {{ request('spp_id') == $spp->spp_id ? 'selected' : '' }} data-type="spp">
                                            {{ $spp->nama }} ({{ $spp->tahun_ajaran }})
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                            
                            <!-- Group for PPDB -->
                            <optgroup label="PPDB & Biaya Lainnya">
                                @foreach($sppList as $spp)
                                    @if(strpos(strtolower($spp->nama), 'spp') === false)
                                        <option value="{{ $spp->spp_id }}" {{ request('spp_id') == $spp->spp_id ? 'selected' : '' }} data-type="other">
                                            {{ $spp->nama }} ({{ $spp->tahun_ajaran }})
                                        </option>
                                    @endif
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    
                    <a href="{{ route('admin.laporan.siswa-menunggak') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm py-2 px-3 rounded-md shadow-sm transition-all duration-200 flex items-center">
                        <i class="fas fa-times text-xs mr-1.5"></i>Reset
                    </a>
                </form>
            </div>

            <!-- Results table with modern styling -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-fixed">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[5%]">No</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[10%]">NIS</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Nama</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[8%]">Kelas</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[32%]">Item Tunggakan</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Total Tunggakan</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @if(count($siswa) > 0)
                            @php $no = 1; @endphp
                            @foreach($siswaByKelas as $namaKelas => $siswaDiKelas)
                            <tr class="bg-red-50 dark:bg-red-900">
                                <td colspan="7" class="py-2 px-4 text-sm font-bold text-red-800 dark:text-red-200">
                                    Kelas {{ $namaKelas }} ({{ count($siswaDiKelas) }} siswa)
                                </td>
                            </tr>
                            @foreach($siswaDiKelas as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $no++ }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $s->nis }}</td>
                            <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $s->nama }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $s->kelas }}</td>
                            <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                                    @php
                                        $sppItems = $tunggakanDetails[$s->siswa_id]['items']->filter(function($item) {
                                            return strpos(strtolower($item->nama), 'spp') !== false;
                                        });
                                        
                                        $ppdbItems = $tunggakanDetails[$s->siswa_id]['items']->filter(function($item) {
                                            return strpos(strtolower($item->nama), 'spp') === false;
                                        });
                                        
                                        $totalSpp = $sppItems->sum('nominal');
                                        $totalPpdb = $ppdbItems->sum('nominal');
                                    @endphp
                                    
                                    @if($sppItems->count() > 0)
                                        <div class="mb-1">
                                            <div class="font-medium text-blue-600 dark:text-blue-400 mb-1 border-b border-gray-200 dark:border-gray-700 pb-1">
                                                SPP Bulanan ({{ $sppItems->count() }} item)
                                            </div>
                                            <div class="space-y-1 pl-2">
                                                @foreach($sppItems as $item)
                                                    <div class="flex justify-between text-xs">
                                                        <span>{{ $item->nama }}</span>
                                                        <span>Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                                    </div>
                                                @endforeach
                                                <div class="flex justify-between text-xs font-medium pt-1 border-t border-gray-100 dark:border-gray-700">
                                                    <span>Subtotal SPP</span>
                                                    <span>Rp {{ number_format($totalSpp, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($ppdbItems->count() > 0)
                                        <div>
                                            <div class="font-medium text-blue-600 dark:text-blue-400 mb-1 border-b border-gray-200 dark:border-gray-700 pb-1">
                                                PPDB & Biaya Lainnya ({{ $ppdbItems->count() }} item)
                                            </div>
                                            <div class="space-y-1 pl-2">
                                                @foreach($ppdbItems as $item)
                                                    <div class="flex justify-between text-xs">
                                                        <span>{{ $item->nama }}</span>
                                                        <span>Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                                    </div>
                                                @endforeach
                                                <div class="flex justify-between text-xs font-medium pt-1 border-t border-gray-100 dark:border-gray-700">
                                                    <span>Subtotal Biaya Lainnya</span>
                                                    <span>Rp {{ number_format($totalPpdb, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-medium text-red-600 dark:text-red-400">
                                Rp {{ number_format($tunggakanDetails[$s->siswa_id]['total'], 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100">
                                    Menunggak
                                </span>
                            </td>
                        </tr>
                        </tr>
                            @endforeach
                            @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="py-4 px-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-5">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p>Tidak ada siswa yang menunggak</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Semua siswa telah melunasi pembayaran</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        async function downloadPdfFile(url, filename) {
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/pdf' }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengunduh file PDF');
                }

                const blob = await response.blob();
                const objectUrl = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = objectUrl;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(objectUrl);
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'PDF tidak dapat diunduh. Silakan coba lagi.',
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when select changes or search input changes
            const select = document.getElementById('kelas');
            select.addEventListener('change', function() {
                this.form.submit();
            });
            
            const sppSelect = document.getElementById('spp_id');
            sppSelect.addEventListener('change', function() {
                this.form.submit();
            });

            // Add debounce to search input
            const searchInput = document.getElementById('search');
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        });
    </script>
    @endpush
</x-app-layout>