<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="p-0 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-t-lg">
                        <h3 class="text-lg font-semibold text-center">Daftar Pembayaran</h3>
                    </div>
                    
                    <!-- Action Buttons and Filter -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm mb-3">
                        <div class="flex flex-wrap justify-between items-center">
                            <!-- Filter Status -->
                            <form action="{{ route('admin.pembayaran.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                                <div class="flex items-center">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 mr-2">
                                        <i class="fas fa-filter"></i>
                                        <span class="ml-1">Status:</span>
                                    </span>
                                    <select name="status" id="status_filter" class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                    
                                    <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-2 rounded-md shadow-sm transition-all duration-200 flex items-center">
                                        <i class="fas fa-search text-xs"></i>
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.pembayaran.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white text-sm py-2 px-3 rounded-md shadow-sm transition-all duration-200 flex items-center">
                                    <i class="fas fa-cash-register mr-1"></i>
                                    <span>Pembayaran Manual</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if(session('success'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                    @endif

                    <div class="overflow-x-auto rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 table-auto">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">No</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Nama Siswa</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Kelas</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Jenis Tagihan</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Total Tagihan</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Total Bayar</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Tahun Ajaran</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Status</th>
                                    <th scope="col" class="py-2 px-3 whitespace-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pembayaran as $index => $item)
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="py-2 px-3 whitespace-nowrap">{{ $index + 1 }}</td>
                                    <td class="py-2 px-3 whitespace-nowrap">{{ $item->siswa->nama }}</td>
                                    <td class="py-2 px-3 whitespace-nowrap">{{ $item->siswa->kelas->nama_kelas ?? $item->siswa->kelas }}</td>
                                    <td class="py-2 px-3 whitespace-nowrap">
                                        @if($item->pembayaranDetail && $item->pembayaranDetail->count() > 0)
                                            <button type="button" onclick="showPaymentItems({{ $item->pembayaran_id }})" class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300 flex items-center hover:bg-blue-200 dark:hover:bg-blue-800 cursor-pointer">
                                                <i class="fas fa-list-ul mr-1"></i> {{ $item->pembayaranDetail->count() }} item
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-500">Tidak Diketahui</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 whitespace-nowrap">Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</td>
                                    <td class="py-2 px-3 whitespace-nowrap">Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</td>
                                    <td class="py-2 px-3 whitespace-nowrap">{{ $item->tahun_ajaran ?? 'N/A' }}</td>
                                    <td class="py-2 px-3 whitespace-nowrap">
                                        @php
                                            $statusColor = [
                                                'pending' => 'yellow',
                                                'lunas' => 'green',
                                                'ditolak' => 'red',
                                                'belum_bayar' => 'blue'
                                            ][$item->status_pembayaran] ?? 'gray';
                                            
                                            $statusText = [
                                                'pending' => 'Pending',
                                                'lunas' => 'Sudah Bayar',
                                                'ditolak' => 'Ditolak',
                                                'belum_bayar' => 'Belum Bayar'
                                            ][$item->status_pembayaran] ?? $item->status_pembayaran;
                                        @endphp
                                        <span class="inline-flex items-center bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 text-xs font-medium px-2 py-0.5 rounded-full dark:bg-{{ $statusColor }}-900 dark:text-{{ $statusColor }}-300">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 whitespace-nowrap">
                                        <div class="flex items-center space-x-2">
                                        @if($item->status_pembayaran == 'lunas')
                                            <a href="{{ route('admin.pembayaran.show', $item->pembayaran_id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded flex items-center">
                                                <i class="fas fa-check-double mr-1"></i> Verified
                                            </a>
                                        @elseif($item->status_pembayaran == 'pending')
                                            <a href="{{ route('admin.pembayaran.show', $item->pembayaran_id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded flex items-center">
                                                <i class="fas fa-check-circle mr-1"></i> Verifikasi
                                            </a>
                                        @elseif($item->status_pembayaran == 'ditolak')
                                            <a href="{{ route('admin.pembayaran.show', $item->pembayaran_id) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded flex items-center">
                                                <i class="fas fa-times-circle mr-1"></i> Ditolak
                                            </a>
                                        @else
                                            <a href="{{ route('admin.pembayaran.show', $item->pembayaran_id) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded flex items-center">
                                                <i class="fas fa-eye mr-1"></i> Detail
                                            </a>
                                        @endif

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.pembayaran.destroy', $item->pembayaran_id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-confirm bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded flex items-center">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="py-4 px-6 text-center" colspan="9">Tidak ada data Pembayaran</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pembayaran->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-confirm');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data pembayaran ini akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
        
        // Function to show payment items in a modal
        function showPaymentItems(paymentId) {
            // Fetch payment details via AJAX
            fetch(`/admin/pembayaran/${paymentId}/items`)
                .then(response => response.json())
                .then(data => {
                    // Create item list HTML
                    let itemsHtml = '';
                    if (data.items && data.items.length > 0) {
                        itemsHtml = '<ul class="space-y-2">';
                        data.items.forEach(item => {
                            itemsHtml += `
                                <li class="flex justify-between items-center border-b pb-2">
                                    <span class="font-medium">${item.spp ? item.spp.nama : 'Item Pembayaran'}</span>
                                    <span>Rp ${new Intl.NumberFormat('id-ID').format(item.biaya)}</span>
                                </li>
                            `;
                        });
                        itemsHtml += '</ul>';
                    } else {
                        itemsHtml = '<p class="text-gray-500">Tidak ada item pembayaran.</p>';
                    }
                    
                    // Show modal with SweetAlert2
                    Swal.fire({
                        title: 'Detail Item Pembayaran',
                        html: itemsHtml,
                        width: 600,
                        showCloseButton: true,
                        showConfirmButton: false,
                        customClass: {
                            container: 'payment-items-modal'
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching payment items:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Gagal memuat detail pembayaran.',
                        icon: 'error'
                    });
                });
        }
    </script>
    @endpush
</x-app-layout>