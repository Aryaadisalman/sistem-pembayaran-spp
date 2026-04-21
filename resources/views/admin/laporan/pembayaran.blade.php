<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-6 border border-gray-200 rounded-xl bg-white mt-14 shadow-sm">
            <!-- Header with title and actions -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                    <i class="fas fa-money-check-alt text-blue-500 mr-2"></i>Laporan Siswa Sudah Bayar
                </h2>
                <div class="flex space-x-2">
                    <form action="{{ route('admin.laporan.pembayaran') }}" method="get" class="inline-flex">
                        <input type="hidden" name="download" value="1">
                        @if(request('dari_tanggal'))
                            <input type="hidden" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
                        @endif
                        @if(request('sampai_tanggal'))
                            <input type="hidden" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}">
                        @endif
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-3 rounded-md shadow-sm transition-all duration-200 flex items-center">
                            <i class="fas fa-download text-xs mr-1.5"></i>Download PDF
                        </button>
                    </form>
                </div>
            </div>

            <!-- Date filter section -->
            <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg mb-5 border border-gray-200 dark:border-gray-600">
                <form action="{{ route('admin.laporan.pembayaran') }}" method="get" class="flex flex-wrap gap-3 items-end">
                    <div class="flex-1 min-w-[150px]">
                        <label for="dari_tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" id="dari_tanggal" value="{{ request('dari_tanggal') }}" 
                            class="text-sm w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label for="sampai_tanggal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" id="sampai_tanggal" value="{{ request('sampai_tanggal') }}" 
                            class="text-sm w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-3 rounded-md shadow-sm transition-all duration-200 flex items-center">
                            <i class="fas fa-search text-xs mr-1.5"></i>Filter
                        </button>
                        <a href="{{ route('admin.laporan.pembayaran') }}" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm py-2 px-3 rounded-md shadow-sm transition-all duration-200 flex items-center ml-2">
                            <i class="fas fa-times text-xs mr-1.5"></i>Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results table with modern styling -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 table-fixed">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[5%]">No</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[12%]">Tanggal</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[18%]">Siswa</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[10%]">Kelas</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[25%]">Item Pembayaran</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Total</th>
                            <th scope="col" class="py-3 px-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-[15%]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($pembayaran as $index => $p)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($p->tanggal_bayar)->format('d/m/Y') }}</td>
                            <td class="py-3 px-4 text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $p->siswa->nama }}</td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $p->siswa->kelas }}</td>
                            <td class="py-3 px-4 text-sm text-gray-500 dark:text-gray-400">
                                <div class="space-y-1 max-h-[150px] overflow-y-auto pr-1">
                                    @foreach($p->pembayaranDetail as $detail)
                                        <div class="border-b border-gray-100 dark:border-gray-700 pb-1 last:border-0 last:pb-0">
                                            {{ $detail->spp->nama }}
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap text-sm font-medium text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($p->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                    Sudah Bayar
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-4 px-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center py-5">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p>Tidak ada data pembayaran</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Silahkan ubah filter atau periksa data pembayaran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination if exists -->
            @if(isset($pembayaran) && $pembayaran instanceof \Illuminate\Pagination\LengthAwarePaginator && $pembayaran->hasPages())
            <div class="mt-4">
                {{ $pembayaran->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
