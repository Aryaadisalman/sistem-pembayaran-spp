<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="p-0 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-t-lg flex items-center justify-center">
                        <h3 class="text-lg font-semibold mb-4">Edit Data SPP / PPDB / DU</h3>
                    </div>
                    <form action="{{ route('admin.spp.update', $spp->spp_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Jenis -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Tagihan</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="radio" name="jenis" id="jenis_spp" value="spp"
                                        {{ old('jenis', $spp->jenis) == 'spp' ? 'checked' : '' }}
                                        class="mr-2" onchange="handleJenisChange('spp')">
                                    <label for="jenis_spp" class="text-sm font-medium text-gray-700 dark:text-gray-300">SPP</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="jenis" id="jenis_ppdb" value="ppdb"
                                        {{ old('jenis', $spp->jenis) == 'ppdb' ? 'checked' : '' }}
                                        class="mr-2" onchange="handleJenisChange('ppdb')">
                                    <label for="jenis_ppdb" class="text-sm font-medium text-gray-700 dark:text-gray-300">PPDB</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="jenis" id="jenis_du" value="du"
                                        {{ old('jenis', $spp->jenis) == 'du' ? 'checked' : '' }}
                                        class="mr-2" onchange="handleJenisChange('du')">
                                    <label for="jenis_du" class="text-sm font-medium text-gray-700 dark:text-gray-300">DU (Daftar Ulang)</label>
                                </div>
                            </div>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $spp->nama) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tahun Ajaran -->
                        <div class="mb-4">
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Ajaran</label>
                            <select id="tahun_ajaran" name="tahun_ajaran" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Pilih Tahun Ajaran</option>
                                @for($i = date('Y'); $i <= date('Y')+5; $i++)
                                    <option value="{{ $i }}/{{ $i+1 }}"
                                        {{ old('tahun_ajaran', $spp->tahun_ajaran) == ($i . '/' . ($i+1)) ? 'selected' : '' }}>
                                        {{ $i }}/{{ $i+1 }}
                                    </option>
                                @endfor
                            </select>
                            @error('tahun_ajaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nominal -->
                        <div class="mb-4">
                            <label for="nominal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Nominal (Rp)</label>
                            <input type="number" name="nominal" id="nominal" value="{{ old('nominal', $spp->nominal) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('nominal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Angsuran - hanya muncul jika DU -->
                        <div class="mb-4" id="angsuran_container" style="{{ old('jenis', $spp->jenis) == 'du' ? '' : 'display:none;' }}">
                            <label for="max_angsuran" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Maksimal Angsuran <span class="text-orange-500">(khusus DU)</span>
                            </label>
                            <input type="number" name="max_angsuran" id="max_angsuran"
                                value="{{ old('max_angsuran', $spp->max_angsuran) }}"
                                min="1" max="24"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: 3">
                            <p class="mt-1 text-xs text-gray-500">Nominal per angsuran = Total Nominal ÷ Jumlah Angsuran</p>
                            @error('max_angsuran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="is_aktif" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select id="is_aktif" name="is_aktif" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="1" {{ old('is_aktif', $spp->is_aktif) == 1 ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_aktif', $spp->is_aktif) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.spp.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function handleJenisChange(jenis) {
            const container = document.getElementById('angsuran_container');
            const input = document.getElementById('max_angsuran');
            if (jenis === 'du') {
                container.style.display = 'block';
                input.required = true;
            } else {
                container.style.display = 'none';
                input.required = false;
            }
        }
    </script>
    @endpush
</x-app-layout>