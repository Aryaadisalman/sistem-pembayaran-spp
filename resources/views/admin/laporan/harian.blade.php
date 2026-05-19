<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">

            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                    <i class="fas fa-calendar-day mr-2 text-blue-500"></i>
                    Laporan Harian Pembayaran
                </h2>
                <a href="{{ route('admin.laporan.harian', array_merge(request()->query(), ['download' => 1])) }}"
                   class="bg-red-500 hover:bg-red-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm flex items-center gap-1 transition-all">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>

            {{-- Filter --}}
            <form method="GET" action="{{ route('admin.laporan.harian') }}" class="mb-6">
                <div class="flex flex-wrap gap-3 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                            class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                            class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-4 rounded-md flex items-center gap-1 transition-all">
                        <i class="fas fa-search text-xs"></i> Filter
                    </button>
                    <a href="{{ route('admin.laporan.harian') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm py-2 px-4 rounded-md flex items-center gap-1 transition-all">
                        <i class="fas fa-times text-xs"></i> Reset
                    </a>
                </div>
            </form>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs text-blue-600 font-medium mb-1">Total Transaksi</p>
                    <p class="text-2xl font-bold text-blue-800">{{ $totalTransaksi }}</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                    <p class="text-xs text-green-600 font-medium mb-1">Grand Total</p>
                    <p class="text-2xl font-bold text-green-800">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Tabel per hari --}}
            @forelse($perHari as $hari)
                <div class="mb-8">
                    {{-- Header hari --}}
                    <div class="flex justify-between items-center bg-gray-100 dark:bg-gray-700 px-4 py-2.5 rounded-t-lg border border-gray-200 dark:border-gray-600">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-day text-blue-500 text-sm"></i>
                            <span class="font-semibold text-sm text-gray-800 dark:text-gray-200">
                                {{ $hari['label'] }}
                            </span>
                            <span class="bg-blue-100 text-blue-700 text-xs px-2 py-0.5 rounded-full">
                                {{ $hari['jumlah'] }} transaksi
                            </span>
                        </div>
                        <div class="text-sm font-bold text-green-700 dark:text-green-400">
                            Total: Rp {{ number_format($hari['total'], 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Tabel transaksi hari ini --}}
                    <div class="border border-t-0 border-gray-200 dark:border-gray-600 rounded-b-lg overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-xs text-gray-500 uppercase">
                                <tr>
                                    <th class="px-4 py-2">No</th>
                                    <th class="px-4 py-2">Nama Siswa</th>
                                    <th class="px-4 py-2">Kelas</th>
                                    <th class="px-4 py-2">Item Pembayaran</th>
                                    <th class="px-4 py-2 text-right">Total Bayar</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($hari['pembayaran'] as $i => $p)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-2 text-gray-500">{{ $i + 1 }}</td>
                                    <td class="px-4 py-2 font-medium text-gray-800 dark:text-gray-200">
                                        {{ $p->siswa->nama ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-gray-600">{{ $p->siswa->kelas ?? '-' }}</td>
                                    <td class="px-4 py-2 text-gray-600">
                                        @foreach($p->pembayaranDetail as $detail)
                                            <span class="block text-xs">{{ $detail->spp->nama ?? '-' }}</span>
                                        @endforeach
                                        @foreach($p->angsuranDu as $du)
                                            <span class="block text-xs text-orange-600">
                                                {{ $du->spp->nama ?? 'DU' }} - Angsuran ke-{{ $du->angsuran_ke }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-4 py-2 text-right font-semibold text-gray-800 dark:text-gray-200">
                                        Rp {{ number_format($p->total_bayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            {{-- Total baris bawah --}}
                            <tfoot>
                                <tr class="bg-green-50 dark:bg-green-900/20 font-semibold">
                                    <td colspan="4" class="px-4 py-2 text-right text-green-700 dark:text-green-400">
                                        Total {{ $hari['label'] }}:
                                    </td>
                                    <td class="px-4 py-2 text-right text-green-700 dark:text-green-400">
                                        Rp {{ number_format($hari['total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-calendar-times text-4xl mb-3"></i>
                    <p class="text-sm">Tidak ada data pembayaran</p>
                    @if(request('dari_tanggal') || request('sampai_tanggal'))
                        <p class="text-xs mt-1">Coba ubah filter tanggal</p>
                    @endif
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
