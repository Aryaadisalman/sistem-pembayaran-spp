<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 rounded-lg bg-white mt-14">
            <!-- Header -->
            <div class="bg-gray-100 p-4 rounded-t-lg">
                <h2 class="text-xl font-semibold text-gray-800 text-center">Tambah Akun Baru</h2>
            </div>

            <div class="p-6">
                @if($errors->any())
                    <script>
                        Swal.fire({
                            title: "Gagal menambahkan akun!",
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

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required onchange="toggleSiswaFields()">
                                <option value="">Pilih Role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kepsek" {{ old('role') == 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                        </div>

                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                            <p class="mt-1 text-sm text-gray-500">Password minimal 3 karakter</p>
                        </div>

                        <div id="nisField" style="display: none;">
                            <label for="nis" class="block text-sm font-medium text-gray-700">NIS</label>
                            <input type="text" name="nis" id="nis" value="{{ old('nis') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        </div>

                        <div id="kelasField" style="display: none;">
                            <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                            <select name="kelas" id="kelas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" disabled {{ old('kelas') == '' ? 'selected' : '' }}>Pilih Kelas</option>
                                <optgroup label="Kelas X">
                                                                        <option value="X RPL 1" {{ old('kelas') == 'X RPL 1' ? 'selected' : '' }}>X RPL 1</option>
                                    <option value="X RPL 2" {{ old('kelas') == 'X RPL 2' ? 'selected' : '' }}>X RPL 2</option>
                                    <option value="X TAV" {{ old('kelas') == 'X TAV' ? 'selected' : '' }}>X TAV</option>
                                    <option value="X TMI 1" {{ old('kelas') == 'X TMI 1' ? 'selected' : '' }}>X TMI 1</option>
                                    <option value="X TMI 2" {{ old('kelas') == 'X TMI 2' ? 'selected' : '' }}>X TMI 2</option>
                                    <option value="X TKR 1" {{ old('kelas') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                                    <option value="X TKR 2" {{ old('kelas') == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                                    <option value="X TKR 3" {{ old('kelas') == 'X TKR 3' ? 'selected' : '' }}>X TKR 3</option>
                                    <option value="X TKR 4" {{ old('kelas') == 'X TKR 4' ? 'selected' : '' }}>X TKR 4</option>
                                    <option value="X TKR 5" {{ old('kelas') == 'X TKR 5' ? 'selected' : '' }}>X TKR 5</option>
                                    <option value="XI RPL" {{ old('kelas') == 'XI RPL' ? 'selected' : '' }}>XI RPL</option>
                                    <option value="XI TAV" {{ old('kelas') == 'XI TAV' ? 'selected' : '' }}>XI TAV</option>
                                    <option value="XI TMI 1" {{ old('kelas') == 'XI TMI 1' ? 'selected' : '' }}>XI TMI 1</option>
                                    <option value="XI TMI 2" {{ old('kelas') == 'XI TMI 2' ? 'selected' : '' }}>XI TMI 2</option>
                                    <option value="XI TKR 1" {{ old('kelas') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                                    <option value="XI TKR 2" {{ old('kelas') == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                                    <option value="XI TKR 3" {{ old('kelas') == 'XI TKR 3' ? 'selected' : '' }}>XI TKR 3</option>
                                    <option value="XI TKR 4" {{ old('kelas') == 'XI TKR 4' ? 'selected' : '' }}>XI TKR 4</option>
                                    <option value="XI TKR 5" {{ old('kelas') == 'XI TKR 5' ? 'selected' : '' }}>XI TKR 5</option>
                                    <option value="XII RPL" {{ old('kelas') == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
                                    <option value="XII TAV" {{ old('kelas') == 'XII TAV' ? 'selected' : '' }}>XII TAV</option>
                                    <option value="XII TMI 1" {{ old('kelas') == 'XII TMI 1' ? 'selected' : '' }}>XII TMI 1</option>
                                    <option value="XII TMI 2" {{ old('kelas') == 'XII TMI 2' ? 'selected' : '' }}>XII TMI 2</option>
                                    <option value="XII TKR 1" {{ old('kelas') == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                                    <option value="XII TKR 2" {{ old('kelas') == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                                    <option value="XII TKR 3" {{ old('kelas') == 'XII TKR 3' ? 'selected' : '' }}>XII TKR 3</option>
                                    <option value="XII TKR 4" {{ old('kelas') == 'XII TKR 4' ? 'selected' : '' }}>XII TKR 4</option>
                                    <option value="XII TKR 5" {{ old('kelas') == 'XII TKR 5' ? 'selected' : '' }}>XII TKR 5</option>
                                </optgroup>
                            </select>
                        </div>



                        <div id="tanggalMasukField" style="display: none;">
                            <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div id="noTelpField" style="display: none;">
                            <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div id="alamatField" style="display: none;">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" id="alamat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3">{{ old('alamat') }}</textarea>
                        </div>
                    </div>

                    <!-- Tombol Kembali & Simpan -->
                    <div class="mt-6 flex justify-start space-x-2">
                        <button type="button" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5" onclick="location.href='{{ route('admin.users.index') }}'">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>

                        <button type="submit" class="text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5">
                            <i class="fas fa-save mr-2"></i>Simpan
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
            const tanggalMasukField = document.getElementById('tanggalMasukField');
            const noTelpField = document.getElementById('noTelpField');
            const alamatField = document.getElementById('alamatField');
            
            // Reset all fields
            nisField.style.display = 'none';
            kelasField.style.display = 'none';
            tanggalMasukField.style.display = 'none';
            noTelpField.style.display = 'none';
            alamatField.style.display = 'none';
            
            // Show fields based on role
            if (role === 'siswa') {
                nisField.style.display = 'block';
                kelasField.style.display = 'block';
                tanggalMasukField.style.display = 'block';
            } else if (role === 'admin' || role === 'kepsek') {
                noTelpField.style.display = 'block';
                alamatField.style.display = 'block';
            }
        }
        
        // Run on page load to handle pre-selected values
        document.addEventListener('DOMContentLoaded', function() {
            toggleSiswaFields();
        });
    </script>
</x-app-layout>