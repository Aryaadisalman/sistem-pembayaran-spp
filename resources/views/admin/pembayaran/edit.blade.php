<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Edit Data Siswa') }}
                </h2>
            </div>

            <div class="p-0 text-gray-900 dark:text-gray-100">
                @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong class="font-bold">Error!</strong>
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <form method="POST" action="{{ route('admin.siswa.update', $siswa->siswa_id) }}">
                    @csrf
                    @method('PUT')
                    <!-- Hidden role field -->
                    <input type="hidden" name="role" value="siswa">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $siswa->nama) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="nis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIS</label>
                            <input type="text" name="nis" id="nis" value="{{ old('nis', $siswa->nis) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">NIS harus 10 digit angka</p>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $siswa->user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>

                        <div>
                            <label for="kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                            <select name="kelas" id="kelas" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="" disabled>Pilih Kelas</option>
                                    <option value="X RPL 1" {{ old('kelas', $siswa->kelas) == 'X RPL 1' ? 'selected' : '' }}>X RPL 1</option>
                                    <option value="X RPL 2" {{ old('kelas', $siswa->kelas) == 'X RPL 2' ? 'selected' : '' }}>X RPL 2</option>
                                    <option value="X TAV" {{ old('kelas', $siswa->kelas) == 'X TAV' ? 'selected' : '' }}>X TAV</option>
                                    <option value="X TMI 1" {{ old('kelas', $siswa->kelas) == 'X TMI 1' ? 'selected' : '' }}>X TMI 1</option>
                                    <option value="X TMI 2" {{ old('kelas', $siswa->kelas) == 'X TMI 2' ? 'selected' : '' }}>X TMI 2</option>
                                    <option value="X TKR 1" {{ old('kelas', $siswa->kelas) == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                                    <option value="X TKR 2" {{ old('kelas', $siswa->kelas) == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                                    <option value="X TKR 3" {{ old('kelas', $siswa->kelas) == 'X TKR 3' ? 'selected' : '' }}>X TKR 3</option>
                                    <option value="X TKR 4" {{ old('kelas', $siswa->kelas) == 'X TKR 4' ? 'selected' : '' }}>X TKR 4</option>
                                    <option value="X TKR 5" {{ old('kelas', $siswa->kelas) == 'X TKR 5' ? 'selected' : '' }}>X TKR 5</option>
                                    <option value="XI RPL" {{ old('kelas', $siswa->kelas) == 'XI RPL' ? 'selected' : '' }}>XI RPL</option>
                                    <option value="XI TAV" {{ old('kelas', $siswa->kelas) == 'XI TAV' ? 'selected' : '' }}>XI TAV</option>
                                    <option value="XI TMI 1" {{ old('kelas', $siswa->kelas) == 'XI TMI 1' ? 'selected' : '' }}>XI TMI 1</option>
                                    <option value="XI TMI 2" {{ old('kelas', $siswa->kelas) == 'XI TMI 2' ? 'selected' : '' }}>XI TMI 2</option>
                                    <option value="XI TKR 1" {{ old('kelas', $siswa->kelas) == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                                    <option value="XI TKR 2" {{ old('kelas', $siswa->kelas) == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                                    <option value="XI TKR 3" {{ old('kelas', $siswa->kelas) == 'XI TKR 3' ? 'selected' : '' }}>XI TKR 3</option>
                                    <option value="XI TKR 4" {{ old('kelas', $siswa->kelas) == 'XI TKR 4' ? 'selected' : '' }}>XI TKR 4</option>
                                    <option value="XI TKR 5" {{ old('kelas', $siswa->kelas) == 'XI TKR 5' ? 'selected' : '' }}>XI TKR 5</option>
                                    <option value="XII RPL" {{ old('kelas', $siswa->kelas) == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
                                    <option value="XII TAV" {{ old('kelas', $siswa->kelas) == 'XII TAV' ? 'selected' : '' }}>XII TAV</option>
                                    <option value="XII TMI 1" {{ old('kelas', $siswa->kelas) == 'XII TMI 1' ? 'selected' : '' }}>XII TMI 1</option>
                                    <option value="XII TMI 2" {{ old('kelas', $siswa->kelas) == 'XII TMI 2' ? 'selected' : '' }}>XII TMI 2</option>
                                    <option value="XII TKR 1" {{ old('kelas', $siswa->kelas) == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                                    <option value="XII TKR 2" {{ old('kelas', $siswa->kelas) == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                                    <option value="XII TKR 3" {{ old('kelas', $siswa->kelas) == 'XII TKR 3' ? 'selected' : '' }}>XII TKR 3</option>
                                    <option value="XII TKR 4" {{ old('kelas', $siswa->kelas) == 'XII TKR 4' ? 'selected' : '' }}>XII TKR 4</option>
                                    <option value="XII TKR 5" {{ old('kelas', $siswa->kelas) == 'XII TKR 5' ? 'selected' : '' }}>XII TKR 5</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk', $siswa->tanggal_masuk) }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-2">
                        <a href="{{ route('admin.siswa.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm transition-all duration-200">
                            <i class="fas fa-times text-xs mr-1"></i>Batal
                        </a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm transition-all duration-200">
                            <i class="fas fa-save text-xs mr-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>