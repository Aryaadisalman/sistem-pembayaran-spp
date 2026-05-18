<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                    Bayar Angsuran DU Ke-{{ $angsuranKe }}
                </h2>
                <a href="{{ route('siswa.angsuran.du.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-2 px-3 rounded-md">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
            @endif

            <!-- Info DU -->
            <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-4 mb-6 border border-blue-200">
                <h3 class="font-semibold text-blue-800 dark:text-blue-200 mb-2">{{ $du->nama }}</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-500 text-xs">Total Tagihan DU</p>
                        <p class="font-bold text-gray-800 dark:text-gray-200">Rp {{ number_format($du->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Nominal Angsuran Ke-{{ $angsuranKe }}</p>
                        <p class="font-bold text-blue-600">Rp {{ number_format($nominalPerAngsuran, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Angsuran</p>
                        <p class="font-bold text-gray-800 dark:text-gray-200">Ke-{{ $angsuranKe }} dari {{ $du->max_angsuran }}x</p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-xs">Tahun Ajaran</p>
                        <p class="font-bold text-gray-800 dark:text-gray-200">{{ $du->tahun_ajaran }}</p>
                    </div>
                </div>
            </div>

            <!-- Form Bayar -->
            <form action="{{ route('siswa.angsuran.du.store', $du->spp_id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Jumlah Bayar (Rp)
                    </label>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                        value="{{ old('jumlah_bayar', round($nominalPerAngsuran)) }}"
                        min="1"
                        class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                        required>
                    <p class="text-xs text-gray-500 mt-1">Nominal angsuran: Rp {{ number_format($nominalPerAngsuran, 0, ',', '.') }}</p>
                    @error('jumlah_bayar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="bukti_bayar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Upload Bukti Transfer
                    </label>
                    <input type="file" name="bukti_bayar" id="bukti_bayar" accept="image/jpeg,image/png,image/jpg"
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600"
                        required>
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG (Maks. 2MB)</p>
                    @error('bukti_bayar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end space-x-2">
                    <a href="{{ route('siswa.angsuran.du.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-sm py-2 px-4 rounded-md">Batal</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-sm py-2 px-4 rounded-md">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>