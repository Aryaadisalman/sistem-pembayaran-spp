<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="p-0 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Detail Pembayaran</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.pembayaran.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="text-md font-semibold mb-4 border-b pb-2">Informasi Pembayaran</h4>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Pembayaran</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->pembayaran_id }}</div>
                                
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Bayar</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->tanggal_bayar ? date('d F Y', strtotime($pembayaran->tanggal_bayar)) : '-' }}</div>

                                @if($pembayaran->bulan)
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Bulan SPP</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->bulan }}</div>
                                @endif
                                
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</div>
                                <div>
                                    @if($pembayaran->status_pembayaran == 'pending')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                                            Pending
                                        </span>
                                    @elseif($pembayaran->status_pembayaran == 'lunas')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            Sudah Bayar
                                        </span>
                                    @elseif($pembayaran->status_pembayaran == 'belum_bayar')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                            Belum Bayar
                                        </span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">
                                            Ditolak
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->admin->nama ?? 'N/A' }}</div>
                                
                                @if($pembayaran->status_pembayaran == 'ditolak')
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Alasan Penolakan</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->keterangan }}</div>
                                @endif
                            </div>
                            
                            <h4 class="text-md font-semibold mb-4 border-b pb-2 mt-6">Informasi Siswa</h4>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Siswa</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->siswa->nama }}</div>
                                
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">NIS</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->siswa->nis }}</div>
                                
                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Kelas</div>
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $pembayaran->siswa->kelas->nama_kelas ?? $pembayaran->siswa->kelas }}</div>
                            </div>
                            
                            <h4 class="text-md font-semibold mb-4 border-b pb-2 mt-6">Detail Pembayaran</h4>
                            
                            <div class="space-y-4">
                                @php
                                    $totalItemSpp = $pembayaran->pembayaranDetail ? $pembayaran->pembayaranDetail->count() : 0;
                                    $totalItemDu  = $pembayaran->angsuranDu ? $pembayaran->angsuranDu->count() : 0;
                                    $totalItem    = $totalItemSpp + $totalItemDu;
                                    $totalBiaya   = ($pembayaran->pembayaranDetail ? $pembayaran->pembayaranDetail->sum('biaya') : 0)
                                                  + ($pembayaran->angsuranDu ? $pembayaran->angsuranDu->sum('nominal_angsuran') : 0);
                                @endphp

                                @if($totalItem > 0)
                                    <!-- Ringkasan Pembayaran -->
                                    <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg border border-blue-200 dark:border-blue-800 mb-4">
                                        <h5 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2 border-b border-blue-200 dark:border-blue-700 pb-1">Ringkasan Pembayaran</h5>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Item:</div>
                                            <div class="text-sm font-semibold text-blue-800 dark:text-blue-200">{{ $totalItem }} item</div>

                                            <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Biaya:</div>
                                            <div class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                                                Rp {{ number_format($totalBiaya, 0, ',', '.') }}
                                            </div>

                                            <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Total Dibayar:</div>
                                            <div class="text-sm font-semibold text-blue-800 dark:text-blue-200">
                                                Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}
                                            </div>

                                            @if($pembayaran->total_bayar < $totalBiaya)
                                                <div class="text-sm font-medium text-red-600 dark:text-red-400">Kekurangan:</div>
                                                <div class="text-sm font-semibold text-red-600 dark:text-red-400">
                                                    Rp {{ number_format($totalBiaya - $pembayaran->total_bayar, 0, ',', '.') }}
                                                </div>
                                            @elseif($pembayaran->total_bayar > $totalBiaya)
                                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Kelebihan:</div>
                                                <div class="text-sm font-semibold text-blue-600 dark:text-blue-400">
                                                    Rp {{ number_format($pembayaran->total_bayar - $totalBiaya, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Loop SPP/PPDB --}}
                                    @foreach($pembayaran->pembayaranDetail as $detail)
                                        <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Item</div>
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $detail->spp->nama ?? 'Item Pembayaran' }}</div>

                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Biaya</div>
                                                <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->biaya, 0, ',', '.') }}</div>

                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Bayar</div>
                                                <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($detail->jumlah_bayar, 0, ',', '.') }}</div>

                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</div>
                                                <div>
                                                    @if($detail->status_pembayaran == 'pending')
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                                                    @elseif($detail->status_pembayaran == 'lunas')
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Lunas</span>
                                                    @elseif($detail->status_pembayaran == 'belum_bayar')
                                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Belum Bayar</span>
                                                    @else
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Ditolak</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Loop DU --}}
                                    @foreach($pembayaran->angsuranDu as $du)
                                        <div class="bg-white dark:bg-gray-600 rounded-lg p-4 border border-orange-200 dark:border-orange-700">
                                            <div class="grid grid-cols-2 gap-2">
                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Item</div>
                                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $du->spp->nama ?? 'DU' }} - Angsuran ke-{{ $du->angsuran_ke }}
                                                </div>

                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Biaya</div>
                                                <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($du->nominal_angsuran, 0, ',', '.') }}</div>

                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Bayar</div>
                                                <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($du->nominal_angsuran, 0, ',', '.') }}</div>

                                                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</div>
                                                <div>
                                                    @if($du->status == 'pending')
                                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">Pending</span>
                                                    @elseif($du->status == 'lunas')
                                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">Lunas</span>
                                                    @else
                                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Ditolak</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                @else
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tagihan</div>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($pembayaran->total_tagihan, 0, ',', '.') }}</div>

                                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Bayar</div>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</div>

                                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Sisa Pembayaran</div>
                                        <div class="text-sm text-gray-900 dark:text-gray-100">Rp {{ number_format($pembayaran->total_tagihan - $pembayaran->total_bayar, 0, ',', '.') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h4 class="text-md font-semibold mb-4 border-b pb-2">Bukti Pembayaran</h4>
    
                            @if ($pembayaran->bukti_bayar)
                                @php
                                    // Ambil nama file saja dari path apapun
                                    $buktiRaw = $pembayaran->bukti_bayar;
                                    $buktiRaw = ltrim($buktiRaw, '/');
                                    $buktiRaw = preg_replace('#^storage/bukti_bayar/#', '', $buktiRaw);
                                    $buktiRaw = preg_replace('#^bukti_bayar/#', '', $buktiRaw);
                                    $filename = basename($buktiRaw);
                                    $buktiUrl = route('bukti.show', ['filename' => $filename]);
                                @endphp
                                <a href="{{ $buktiUrl }}" target="_blank">
                                    <img src="{{ $buktiUrl }}" alt="Bukti Pembayaran"
                                        class="w-full object-contain rounded-lg border border-gray-200 cursor-pointer hover:opacity-90 transition"
                                        onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'p-4 bg-red-50 rounded border border-red-200 text-center\'><p class=\'text-red-500 text-sm mb-2\'>Gambar tidak dapat ditampilkan.</p><a href=\'{{ $buktiUrl }}\' target=\'_blank\' class=\'text-blue-500 underline text-sm\'>Coba buka langsung</a></div>';">
                                </a>
                                <p class="text-xs text-gray-400 mt-2 text-center">Klik gambar untuk memperbesar</p>
                            @else
                                <div class="flex items-center justify-center h-64 bg-gray-200 dark:bg-gray-600 rounded">
                                    <p class="text-gray-500 dark:text-gray-400">Tidak ada bukti pembayaran</p>
                                </div>
                            @endif
    
                            @if(in_array($pembayaran->status_pembayaran, ['pending', 'belum_bayar']))
                                <script>
                                    // Modal functions
                                    function openRejectModal() {
                                        document.getElementById('reject-modal').classList.remove('hidden');
                                        document.body.style.overflow = 'hidden';
                                    }

                                    function closeRejectModal() {
                                        document.getElementById('reject-modal').classList.add('hidden');
                                        document.body.style.overflow = 'auto';
                                    }

                                    // Close modal when clicking outside
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const modal = document.getElementById('reject-modal');
                                        if (modal) {
                                            modal.addEventListener('click', function(e) {
                                                if (e.target === this) {
                                                    closeRejectModal();
                                                }
                                            });

                                            // Handle form submission
                                            const rejectForm = document.getElementById('reject-form');
                                            if (rejectForm) {
                                                rejectForm.addEventListener('submit', function(e) {
                                                    e.preventDefault();
                                                    
                                                    // Show loading state
                                                    const submitButton = this.querySelector('button[type="submit"]');
                                                    submitButton.disabled = true;
                                                    submitButton.textContent = 'Mengirim...';

                                                    fetch(this.action, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/x-www-form-urlencoded',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                                        },
                                                        body: new URLSearchParams(new FormData(this))
                                                    })
                                                    .then(response => response.json())
                                                    .then(data => {
                                                        if (data.success) {
                                                            // Show success alert
                                                            Swal.fire({
                                                                icon: 'success',
                                                                title: 'Berhasil',
                                                                text: data.message,
                                                                showConfirmButton: false,
                                                                timer: 2000
                                                            });
                                                            
                                                            // Close modal and redirect
                                                            closeRejectModal();
                                                            window.location.href = data.redirect;
                                                        } else {
                                                            throw new Error(data.message || 'Terjadi kesalahan');
                                                        }
                                                    })
                                                    .catch(error => {
                                                        // Show error message
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Gagal',
                                                            text: error.message,
                                                            showConfirmButton: true
                                                        });
                                                    })
                                                    .finally(() => {
                                                        // Reset button
                                                        submitButton.disabled = false;
                                                        submitButton.textContent = 'Tolak Pembayaran';
                                                    });
                                                });
                                            }
                                        }
                                    });
                                </script>

                                <div class="mt-6 grid grid-cols-2 gap-2">
                                    <!-- Form Terima Pembayaran -->
                                    <form id="accept-form" action="{{ route('admin.pembayaran.updateStatus', $pembayaran->pembayaran_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status_pembayaran" value="lunas">
                                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            <i class="fas fa-check mr-2"></i>Terima Pembayaran
                                        </button>
                                    </form>

                                    <!-- Form Tolak Pembayaran -->
                                    <button type="button" class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="openRejectModal()">
                                        <i class="fas fa-times mr-2"></i>Tolak Pembayaran
                                    </button>
                                </div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const acceptForm = document.getElementById('accept-form');
                                        if (acceptForm) {
                                            acceptForm.addEventListener('submit', function(e) {
                                                e.preventDefault();
                                                
                                                // Show loading state
                                                const submitButton = this.querySelector('button[type="submit"]');
                                                submitButton.disabled = true;
                                                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';

                                                fetch(this.action, {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/x-www-form-urlencoded',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                                    },
                                                    body: new URLSearchParams(new FormData(this))
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        // Show success alert
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Berhasil',
                                                            text: data.message,
                                                            showConfirmButton: false,
                                                            timer: 2000
                                                        });
                                                        
                                                        // Redirect after success
                                                        setTimeout(() => {
                                                            window.location.href = data.redirect;
                                                        }, 1000);
                                                    } else {
                                                        throw new Error(data.message || 'Terjadi kesalahan');
                                                    }
                                                })
                                                .catch(error => {
                                                    // Show error message
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Gagal',
                                                        text: error.message,
                                                        showConfirmButton: true
                                                    });
                                                    
                                                    // Reset button
                                                    submitButton.disabled = false;
                                                    submitButton.innerHTML = '<i class="fas fa-check mr-2"></i>Terima Pembayaran';
                                                });
                                            });
                                        }
                                    });
                                </script>

                                <!-- Reject Modal -->
                                <div id="reject-modal" class="fixed top-0 left-0 right-0 z-50 hidden w-full h-full bg-gray-500 bg-opacity-75 flex items-center justify-center">
                                    <div class="relative w-full max-w-md max-h-full">
                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                            <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeRejectModal()">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                            <div class="p-6 text-center">
                                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Masukkan alasan penolakan pembayaran</h3>
                                                <form id="reject-form" action="{{ route('admin.pembayaran.updateStatus', $pembayaran->pembayaran_id) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status_pembayaran" value="ditolak">
                                                    <div class="relative">
                                                        <textarea id="keterangan" name="keterangan" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tulis alasan penolakan di sini..." required></textarea>
                                                    </div>
                                                    <div class="flex justify-end space-x-4">
                                                        <button type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600" onclick="closeRejectModal()">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                            Tolak Pembayaran
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('reject-modal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeRejectModal();
                }
            });
        }
    });
</script>
@endpush