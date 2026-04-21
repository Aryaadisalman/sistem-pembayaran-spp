<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 rounded-lg bg-white mt-14">
            <!-- Header -->
            <div class="bg-gray-100 p-4 rounded-t-lg">
                <h2 class="text-xl font-semibold text-gray-800 text-center">Edit Akun: {{ $user->nama }}</h2>
            </div>

            @if($errors->any())
                <script>
                    Swal.fire({
                        title: "Gagal mengupdate akun!",
                        icon: "error",
                        draggable: true,
                        html: `<div class="text-left">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $error)
                                    <li class="mb-1">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>`
                    });
                </script>
            @endif

            <div class="p-6">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Password minimal 3 karakter</p>
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required onchange="toggleSiswaFields()">
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kepsek" {{ old('role', $user->role) == 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                        </div>
                        
                        <div id="namaField">
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $user->nama) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div id="nisField" style="display: none;">
                            <label for="nis" class="block text-sm font-medium text-gray-700">NIS</label>
                            <input type="text" name="nis" id="nis" value="{{ old('nis', $user->siswa->nis ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <p class="mt-1 text-xs text-gray-500">NIS harus 10 digit angka</p>
                        </div>

                        <div id="kelasField" style="display: none;">
                            <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas" id="kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" disabled {{ old('kelas', $user->siswa->kelas ?? '') == '' ? 'selected' : '' }}>Pilih Kelas</option>
                                    <option value="X RPL 1" {{ old('kelas', $user->siswa->kelas ?? '') == 'X RPL 1' ? 'selected' : '' }}>X RPL 1</option>
                                    <option value="X RPL 2" {{ old('kelas', $user->siswa->kelas ?? '') == 'X RPL 2' ? 'selected' : '' }}>X RPL 2</option>
                                    <option value="X TAV" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TAV' ? 'selected' : '' }}>X TAV</option>
                                    <option value="X TMI 1" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TMI 1' ? 'selected' : '' }}>X TMI 1</option>
                                    <option value="X TMI 2" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TMI 2' ? 'selected' : '' }}>X TMI 2</option>
                                    <option value="X TKR 1" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                                    <option value="X TKR 2" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                                    <option value="X TKR 3" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TKR 3' ? 'selected' : '' }}>X TKR 3</option>
                                    <option value="X TKR 4" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TKR 4' ? 'selected' : '' }}>X TKR 4</option>
                                    <option value="X TKR 5" {{ old('kelas', $user->siswa->kelas ?? '') == 'X TKR 5' ? 'selected' : '' }}>X TKR 5</option>
                                    <option value="XI RPL" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI RPL' ? 'selected' : '' }}>XI RPL</option>
                                    <option value="XI TAV" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TAV' ? 'selected' : '' }}>XI TAV</option>
                                    <option value="XI TMI 1" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TMI 1' ? 'selected' : '' }}>XI TMI 1</option>
                                    <option value="XI TMI 2" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TMI 2' ? 'selected' : '' }}>XI TMI 2</option>
                                    <option value="XI TKR 1" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                                    <option value="XI TKR 2" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                                    <option value="XI TKR 3" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TKR 3' ? 'selected' : '' }}>XI TKR 3</option>
                                    <option value="XI TKR 4" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TKR 4' ? 'selected' : '' }}>XI TKR 4</option>
                                    <option value="XI TKR 5" {{ old('kelas', $user->siswa->kelas ?? '') == 'XI TKR 5' ? 'selected' : '' }}>XI TKR 5</option>
                                    <option value="XII RPL" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
                                    <option value="XII TAV" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TAV' ? 'selected' : '' }}>XII TAV</option>
                                    <option value="XII TMI 1" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TMI 1' ? 'selected' : '' }}>XII TMI 1</option>
                                    <option value="XII TMI 2" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TMI 2' ? 'selected' : '' }}>XII TMI 2</option>
                                    <option value="XII TKR 1" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                                    <option value="XII TKR 2" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                                    <option value="XII TKR 3" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TKR 3' ? 'selected' : '' }}>XII TKR 3</option>
                                    <option value="XII TKR 4" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TKR 4' ? 'selected' : '' }}>XII TKR 4</option>
                                    <option value="XII TKR 5" {{ old('kelas', $user->siswa->kelas ?? '') == 'XII TKR 5' ? 'selected' : '' }}>XII TKR 5</option>
                            </select>
                        </div>

                        <div id="noTelpField" style="display: none;">
                            <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $user->admin->no_telp ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div id="alamatField" style="display: none;">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3">{{ old('alamat', $user->admin->alamat ?? '') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-start space-x-2">
                        <button type="button" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700" onclick="location.href='{{ route('admin.users.index') }}'">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>
                        
                        <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleSiswaFields() {
            const role = document.getElementById('role').value;
            const nisField = document.getElementById('nisField');
            const kelasField = document.getElementById('kelasField');
            const noTelpField = document.getElementById('noTelpField');
            const alamatField = document.getElementById('alamatField');
            
            // Reset all fields
            nisField.style.display = 'none';
            kelasField.style.display = 'none';
            noTelpField.style.display = 'none';
            alamatField.style.display = 'none';
            
            // Show fields based on role
            if (role === 'siswa') {
                nisField.style.display = 'block';
                kelasField.style.display = 'block';
            } else if (role === 'admin' || role === 'kepsek') {
                noTelpField.style.display = 'block';
                alamatField.style.display = 'block';
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            toggleSiswaFields();
        });
    </script>
</x-app-layout>