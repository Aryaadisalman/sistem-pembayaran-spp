<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">
                Manajemen Angsuran DU
            </h2>

            @if(session('success'))
            <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif

            <!-- Filter Status -->
            <div class="mb-4 flex space-x-2">
                <a href="{{ route('admin.angsuran.du.index') }}"
                    class="px-3 py-1.5 rounded-md text-xs {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Semua
                </a>
                <a href="{{ route('admin.angsuran.du.index', ['status' => 'pending']) }}"
                    class="px-3 py-1.5 rounded-md text-xs {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Menunggu
                </a>
                <a href="{{ route('admin.angsuran.du.index', ['status' => 'lunas']) }}"
                    class="px-3 py-1.5 rounded-md text-xs {{ request('status') === 'lunas' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Lunas
                </a>
                <a href="{{ route('admin.angsuran.du.index', ['status' => 'ditolak']) }}"
                    class="px-3 py-1.5 rounded-md text-xs {{ request('status') === 'ditolak' ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-700' }}">
                    Ditolak
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th class="py-2 px-4">No</th>
                            <th class="py-2 px-4">Siswa</th>
                            <th class="py-2 px-4">DU</th>
                            <th class="py-2 px-4">Angsuran Ke</th>
                            <th class="py-2 px-4">Jumlah Bayar</th>
                            <th class="py-2 px-4">Tanggal</th>
                            <th class="py-2 px-4">Bukti</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($angsuranList as $index => $item)
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50">
                            <td class="py-2.5 px-4">{{ $index + 1 }}</td>
                            <td class="py-2.5 px-4">
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $item->siswa->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $item->siswa->kelas ?? '' }}</p>
                            </td>
                            <td class="py-2.5 px-4">{{ $item->spp->nama ?? '-' }}</td>
                            <td class="py-2.5 px-4">
                                Ke-{{ $item->angsuran_ke }} / {{ $item->spp->max_angsuran ?? '?' }}x
                            </td>
                            <td class="py-2.5 px-4">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="py-2.5 px-4">{{ $item->tanggal_bayar ? $item->tanggal_bayar->format('d/m/Y') : '-' }}</td>
                            <td class="py-2.5 px-4">
                                @if($item->bukti_bayar)
                                    @php
                                        $fn = basename($item->bukti_bayar);
                                    @endphp
                                    <a href="{{ route('bukti.show', ['filename' => $fn]) }}" target="_blank"
                                        class="text-blue-500 hover:underline text-xs">Lihat</a>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4">
                                @if($item->status === 'lunas')
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded">Lunas</span>
                                @elseif($item->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded">Menunggu</span>
                                @elseif($item->status === 'ditolak')
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded">Ditolak</span>
                                @else
                                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-0.5 rounded">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4">
                                @if($item->status === 'pending')
                                <div class="flex space-x-1">
                                    <button onclick="verifyAngsuran({{ $item->angsuran_id }}, 'lunas')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white text-xs p-1.5 rounded-md">
                                        <i class="fas fa-check text-xs"></i>
                                    </button>
                                    <button onclick="verifyAngsuran({{ $item->angsuran_id }}, 'ditolak')"
                                        class="bg-red-500 hover:bg-red-600 text-white text-xs p-1.5 rounded-md">
                                        <i class="fas fa-times text-xs"></i>
                                    </button>
                                </div>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-4 px-6 text-center text-gray-500">Tidak ada data angsuran DU</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $angsuranList->links() }}</div>
        </div>
    </div>

    @push('scripts')
    <script>
        function verifyAngsuran(id, status) {
            const label = status === 'lunas' ? 'Terima' : 'Tolak';
            Swal.fire({
                title: label + ' Angsuran?',
                text: status === 'lunas' ? 'Angsuran akan ditandai sebagai lunas.' : 'Angsuran akan ditolak.',
                icon: status === 'lunas' ? 'success' : 'warning',
                showCancelButton: true,
                confirmButtonColor: status === 'lunas' ? '#3085d6' : '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, ' + label,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/angsuran-du/${id}/verify`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ status: status })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: data.message, timer: 2000, showConfirmButton: false })
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                        }
                    });
                }
            });
        }
    </script>
    @endpush
</x-app-layout>