<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-6">
                Pembayaran DU (Daftar Ulang)
            </h2>

            @if(session('success'))
            <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            @forelse($angsuranList as $item)
            <div class="mb-6 bg-gray-50 dark:bg-gray-700 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">{{ $item['du']->nama }}</h3>
                        <p class="text-xs text-gray-500">Tahun Ajaran: {{ $item['du']->tahun_ajaran }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Total Tagihan</p>
                        <p class="font-bold text-gray-800 dark:text-gray-200">Rp {{ number_format($item['du']->nominal, 0, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Progress Bar -->
                @php
                    $progress = $item['du']->nominal > 0 ? ($item['total_lunas'] / $item['du']->nominal) * 100 : 0;
                @endphp
                <div class="mb-3">
                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                        <span>Terbayar: Rp {{ number_format($item['total_lunas'], 0, ',', '.') }}</span>
                        <span>Sisa: Rp {{ number_format($item['sisa'], 0, ',', '.') }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min($progress, 100) }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Angsuran {{ $item['sudah_bayar'] }} dari {{ $item['max'] }}x</p>
                </div>

                <!-- Daftar Angsuran -->
                @if($item['angsuran']->count() > 0)
                <div class="mb-3">
                    <table class="w-full text-xs text-left text-gray-500">
                        <thead class="bg-gray-100 dark:bg-gray-600">
                            <tr>
                                <th class="py-2 px-3">Angsuran ke</th>
                                <th class="py-2 px-3">Jumlah Bayar</th>
                                <th class="py-2 px-3">Tanggal</th>
                                <th class="py-2 px-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item['angsuran'] as $ang)
                            <tr class="border-b dark:border-gray-600">
                                <td class="py-2 px-3">Ke-{{ $ang->angsuran_ke }}</td>
                                <td class="py-2 px-3">Rp {{ number_format($ang->jumlah_bayar, 0, ',', '.') }}</td>
                                <td class="py-2 px-3">{{ $ang->tanggal_bayar ? $ang->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                                <td class="py-2 px-3">
                                    @if($ang->status === 'lunas')
                                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">Lunas</span>
                                    @elseif($ang->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded">Menunggu</span>
                                    @elseif($ang->status === 'ditolak')
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded">Ditolak</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Tombol Bayar Angsuran -->
                @if($item['sisa'] > 0 && $item['sudah_bayar'] < $item['max'])
                    @php
                        $pendingCount = $item['angsuran']->where('status', 'pending')->count();
                    @endphp
                    @if($pendingCount === 0)
                    <a href="{{ route('siswa.angsuran.du.create', $item['du']->spp_id) }}"
                        class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white text-xs py-2 px-4 rounded-md shadow-sm transition-all">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Bayar Angsuran Ke-{{ $item['sudah_bayar'] + 1 }}
                        (Rp {{ number_format($item['du']->nominal / $item['max'], 0, ',', '.') }})
                    </a>
                    @else
                    <p class="text-xs text-yellow-600 italic">Ada angsuran yang sedang menunggu verifikasi admin.</p>
                    @endif
                @elseif($item['sisa'] <= 0)
                    <p class="text-xs text-blue-600 font-semibold">✓ DU Lunas</p>
                @endif
            </div>
            @empty
            <div class="text-center py-10 text-gray-500">
                <i class="fas fa-file-invoice text-4xl mb-3 text-gray-300"></i>
                <p>Tidak ada tagihan DU saat ini.</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>