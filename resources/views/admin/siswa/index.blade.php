<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Data Siswa
                </h2>
                <div class="flex space-x-2">
                    <button type="button" onclick="openSiswaModal()" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1.5 px-2.5 rounded-md shadow-sm transition-all duration-200 flex items-center">
                        <i class="fas fa-plus text-xs"></i>
                        <span class="ml-1">Siswa</span>
                    </button>
                    <button type="button" onclick="document.getElementById('modalImport').classList.remove('hidden')" class="bg-green-500 hover:bg-green-600 text-white text-xs py-1.5 px-2.5 rounded-md shadow-sm transition-all duration-200 flex items-center">
                        <i class="fas fa-file-excel text-xs"></i>
                        <span class="ml-1">Import Excel</span>
                    </button>
                    <a href="{{ route('admin.siswa.template') }}" class="bg-teal-500 hover:bg-teal-600 text-white text-xs py-1.5 px-2.5 rounded-md shadow-sm transition-all duration-200 flex items-center">
                        <i class="fas fa-download text-xs"></i>
                        <span class="ml-1">Template</span>
                    </a>
                    <button type="button" onclick="downloadPdfFile('{{ route('admin.siswa.export', ['kelas' => request('kelas')]) }}', 'daftar-siswa{{ request('kelas') ? '-kelas-' . request('kelas') : '' }}.pdf')" class="bg-red-500 hover:bg-red-600 text-white text-xs py-1.5 px-2.5 rounded-md shadow-sm transition-all duration-200 flex items-center">
                        <i class="fas fa-file-pdf text-xs"></i>
                        <span class="ml-1">PDF</span>
                    </button>
                    <a href="{{ route('admin.siswa.pengaturan-kenaikan') }}" class="bg-purple-500 hover:bg-purple-600 text-white text-xs py-1.5 px-2.5 rounded-md shadow-sm transition-all duration-200 flex items-center">
                        <i class="fas fa-graduation-cap text-xs"></i>
                        <span class="ml-1">Kenaikan Kelas</span>
                    </a>
                </div>
            </div>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>
            @endif
            
            <!-- Filter Kelas -->
            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm">
                <div class="flex flex-wrap justify-between items-center">
                    <form action="{{ route('admin.siswa.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            <i class="fas fa-filter"></i>
                            <span class="ml-1">Kelas:</span>
                        </div>
                        
                        <select name="kelas" id="kelas_filter" class="text-xs bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md px-2 py-1.5 focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ request('kelas') == '' ? 'selected' : '' }}>Semua Kelas</option>
                            <optgroup label="Kelas X">
                                <option value="X RPL 1" {{ request('kelas') == 'X RPL 1' ? 'selected' : '' }}>X RPL 1</option>
                                <option value="X RPL 2" {{ request('kelas') == 'X RPL 2' ? 'selected' : '' }}>X RPL 2</option>
                                <option value="X TAV" {{ request('kelas') == 'X TAV' ? 'selected' : '' }}>X TAV</option>
                                <option value="X TMI 1" {{ request('kelas') == 'X TMI 1' ? 'selected' : '' }}>X TMI 1</option>
                                <option value="X TMI 2" {{ request('kelas') == 'X TMI 2' ? 'selected' : '' }}>X TMI 2</option>
                                <option value="X TKR 1" {{ request('kelas') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1</option>
                                <option value="X TKR 2" {{ request('kelas') == 'X TKR 2' ? 'selected' : '' }}>X TKR 2</option>
                                <option value="X TKR 3" {{ request('kelas') == 'X TKR 3' ? 'selected' : '' }}>X TKR 3</option>
                                <option value="X TKR 4" {{ request('kelas') == 'X TKR 4' ? 'selected' : '' }}>X TKR 4</option>
                                <option value="X TKR 5" {{ request('kelas') == 'X TKR 5' ? 'selected' : '' }}>X TKR 5</option>
                            </optgroup>
                                <optgroup label="Kelas XI">
                                <option value="XI RPL" {{ request('kelas') == 'XI RPL' ? 'selected' : '' }}>XI RPL</option>
                                <option value="XI TAV" {{ request('kelas') == 'XI TAV' ? 'selected' : '' }}>XI TAV</option>
                                <option value="XI TMI 1" {{ request('kelas') == 'XI TMI 1' ? 'selected' : '' }}>XI TMI 1</option>
                                <option value="XI TMI 2" {{ request('kelas') == 'XI TMI 2' ? 'selected' : '' }}>XI TMI 2</option>
                                <option value="XI TKR 1" {{ request('kelas') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR 1</option>
                                <option value="XI TKR 2" {{ request('kelas') == 'XI TKR 2' ? 'selected' : '' }}>XI TKR 2</option>
                                <option value="XI TKR 3" {{ request('kelas') == 'XI TKR 3' ? 'selected' : '' }}>XI TKR 3</option>
                                <option value="XI TKR 4" {{ request('kelas') == 'XI TKR 4' ? 'selected' : '' }}>XI TKR 4</option>
                                <option value="XI TKR 5" {{ request('kelas') == 'XI TKR 5' ? 'selected' : '' }}>XI TKR 5</option>
                                </optgroup>
                                <optgroup label="Kelas XII">
                                <option value="XII RPL" {{ request('kelas') == 'XII RPL' ? 'selected' : '' }}>XII RPL</option>
                                <option value="XII TAV" {{ request('kelas') == 'XII TAV' ? 'selected' : '' }}>XII TAV</option>
                                <option value="XII TMI 1" {{ request('kelas') == 'XII TMI 1' ? 'selected' : '' }}>XII TMI 1</option>
                                <option value="XII TMI 2" {{ request('kelas') == 'XII TMI 2' ? 'selected' : '' }}>XII TMI 2</option>
                                <option value="XII TKR 1" {{ request('kelas') == 'XII TKR 1' ? 'selected' : '' }}>XII TKR 1</option>
                                <option value="XII TKR 2" {{ request('kelas') == 'XII TKR 2' ? 'selected' : '' }}>XII TKR 2</option>
                                <option value="XII TKR 3" {{ request('kelas') == 'XII TKR 3' ? 'selected' : '' }}>XII TKR 3</option>
                                <option value="XII TKR 4" {{ request('kelas') == 'XII TKR 4' ? 'selected' : '' }}>XII TKR 4</option>
                                <option value="XII TKR 5" {{ request('kelas') == 'XII TKR 5' ? 'selected' : '' }}>XII TKR 5</option>
                                 </optgroup>
                        </select>
                        
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-2 rounded-md shadow-sm transition-all duration-200 flex items-center">
                            <i class="fas fa-search text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="p-0 text-gray-900 dark:text-gray-100">
                @if(request('kelas') || request('kelas') === '' || request('kelas') === null)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 font-header">
                                <tr>
                                    <th scope="col" class="py-2 px-4">
                                        <input type="checkbox" id="select-all" class="w-3.5 h-3.5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-1 dark:bg-gray-700 dark:border-gray-600">
                                    </th>
                                    <th scope="col" class="py-2 px-4">No</th>
                                    <th scope="col" class="py-2 px-4">NIS</th>
                                    <th scope="col" class="py-2 px-4">Nama</th>
                                    <th scope="col" class="py-2 px-4">Kelas</th>
                                    <th scope="col" class="py-2 px-4">Tanggal Masuk</th>
                                    <th scope="col" class="py-2 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="font-body">
                                @php
                                    $isEmptyKelas = request('kelas') === '' || request('kelas') === null;
                                @endphp
                                
                                @if($isEmptyKelas)
                                    @foreach($siswa as $kelasName => $siswaInKelas)
                                        <!-- Kelas header -->
                                        <tr class="bg-indigo-100 dark:bg-indigo-900">
                                            <td colspan="7" class="py-2 px-4 font-bold text-indigo-800 dark:text-indigo-200">
                                                Kelas {{ $kelasName }} ({{ count($siswaInKelas) }} Siswa)
                                            </td>
                                        </tr>
                                        
                                        <!-- Siswa in this class -->
                                        @foreach($siswaInKelas as $index => $item)
                                            <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-sm">
                                                <td class="py-2.5 px-4">
                                                    <input type="checkbox" name="selected_siswa[]" value="{{ $item->siswa_id }}" data-kelas="{{ $item->kelas }}" class="siswa-checkbox w-3.5 h-3.5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-1 dark:bg-gray-700 dark:border-gray-600">
                                                </td>
                                                <td class="py-2.5 px-4">{{ $index + 1 }}</td>
                                                <td class="py-2.5 px-4">{{ $item->nis }}</td>
                                                <td class="py-2.5 px-4">{{ $item->nama }}</td>
                                                <td class="py-2.5 px-4">{{ $item->kelas }}</td>
                                                <td class="py-2.5 px-4">{{ $item->tanggal_masuk ? date('d/m/Y', strtotime($item->tanggal_masuk)) : '-' }}</td>
                                                <td class="py-3 px-4 flex space-x-1">
                                                    <a href="{{ route('admin.siswa.edit', $item->siswa_id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs p-1.5 rounded-md shadow-sm transition-all duration-200">
                                                        <i class="fas fa-edit text-xs"></i>
                                                    </a>
                                                    <form action="{{ route('admin.siswa.destroy', $item->siswa_id) }}" method="POST" class="inline-block delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white text-xs p-1.5 rounded-md shadow-sm transition-all duration-200 delete-btn">
                                                            <i class="fas fa-trash text-xs"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @else
                                    @forelse($siswa as $index => $item)
                                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-sm">
                                            <td class="py-2.5 px-4">
                                                <input type="checkbox" name="selected_siswa[]" value="{{ $item->siswa_id }}" data-kelas="{{ $item->kelas }}" class="siswa-checkbox w-3.5 h-3.5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-1 dark:bg-gray-700 dark:border-gray-600">
                                            </td>
                                            <td class="py-2.5 px-4">{{ $index + 1 }}</td>
                                            <td class="py-2.5 px-4">{{ $item->nis }}</td>
                                            <td class="py-2.5 px-4">{{ $item->nama }}</td>
                                            <td class="py-2.5 px-4">{{ $item->kelas }}</td>
                                            <td class="py-2.5 px-4">{{ $item->tanggal_masuk ? date('d/m/Y', strtotime($item->tanggal_masuk)) : '-' }}</td>
                                            <td class="py-3 px-4 flex space-x-1">
                                                <a href="{{ route('admin.siswa.edit', $item->siswa_id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs p-1.5 rounded-md shadow-sm transition-all duration-200">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>
                                                <form action="{{ route('admin.siswa.destroy', $item->siswa_id) }}" method="POST" class="inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="bg-red-500 hover:bg-red-600 text-white text-xs p-1.5 rounded-md shadow-sm transition-all duration-200 delete-btn">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                            <td class="py-4 px-6 text-center" colspan="7">Tidak ada data siswa</td>
                                        </tr>
                                    @endforelse
                                @endif
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Tidak ada data yang ditampilkan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Silahkan pilih filter kelas untuk menampilkan data siswa.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Import Excel -->
    <div id="modalImport" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-md mx-4">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-base font-semibold text-gray-800 dark:text-gray-200">
                    <i class="fas fa-file-excel text-green-500 mr-2"></i>Import Siswa dari Excel
                </h3>
                <button type="button" onclick="document.getElementById('modalImport').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-5">
                <!-- Info format -->
                <div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-3 mb-4 text-xs text-blue-800 dark:text-blue-200">
                    <p class="font-semibold mb-1">📋 Format kolom Excel:</p>
                    <p>nama | nis | kelas | tanggal_masuk | email | password</p>
                    <p class="mt-1">Format tanggal: <strong>YYYY-MM-DD</strong> (contoh: 2026-07-14)</p>
                    <p class="mt-1">
                        <a href="{{ route('admin.siswa.template') }}" class="underline font-semibold">
                            ⬇️ Download template Excel
                        </a>
                    </p>
                </div>

                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Pilih File Excel (.xlsx / .xls / .csv)
                        </label>
                        <input type="file" name="file_excel" accept=".xlsx,.xls,.csv"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600"
                            required>
                        @error('file_excel')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal ukuran file 5MB</p>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" onclick="document.getElementById('modalImport').classList.add('hidden')"
                            class="bg-gray-500 hover:bg-gray-600 text-white text-sm py-2 px-4 rounded-md">
                            Batal
                        </button>
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-sm py-2 px-4 rounded-md">
                            <i class="fas fa-upload mr-1"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Siswa -->
    <div id="siswa-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-200 dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Tambah Siswa Baru
                    </h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" onclick="closeSiswaModal()">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Tutup modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5">
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
                    
                    <form action="{{ route('admin.siswa.store') }}" method="POST" id="siswaForm">
                        @csrf
                        <!-- Hidden role field -->
                        <input type="hidden" name="role" value="siswa">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                                <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIS</label>
                                <input type="text" name="nis" id="nis" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">NIS harus 10 digit angka</p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>

                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelas</label>
                                <select name="kelas" id="kelas" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="" disabled selected>Pilih Kelas</option>
                                    <optgroup label="Kelas X">
                                                                                <option value="X RPL 1">X RPL 1</option>
                                        <option value="X RPL 2">X RPL 2</option>
                                        <option value="X TAV">X TAV</option>
                                        <option value="X TMI 1">X TMI 1</option>
                                        <option value="X TMI 2">X TMI 2</option>
                                        <option value="X TKR 1">X TKR 1</option>
                                        <option value="X TKR 2">X TKR 2</option>
                                        <option value="X TKR 3">X TKR 3</option>
                                        <option value="X TKR 4">X TKR 4</option>
                                        <option value="X TKR 5">X TKR 5</option>
                                    </optgroup>
                                    <optgroup label="Kelas XI">
                                        <option value="XI RPL">XI RPL</option>
                                        <option value="XI TAV">XI TAV</option>
                                        <option value="XI TMI 1">XI TMI 1</option>
                                        <option value="XI TMI 2">XI TMI 2</option>
                                        <option value="XI TKR 1">XI TKR 1</option>
                                        <option value="XI TKR 2">XI TKR 2</option>
                                        <option value="XI TKR 3">XI TKR 3</option>
                                        <option value="XI TKR 4">XI TKR 4</option>
                                        <option value="XI TKR 5">XI TKR 5</option>
                                    </optgroup>
                                    <optgroup label="Kelas XII">
                                        <option value="XII RPL">XII RPL</option>
                                        <option value="XII TAV">XII TAV</option>
                                        <option value="XII TMI 1">XII TMI 1</option>
                                        <option value="XII TMI 2">XII TMI 2</option>
                                        <option value="XII TKR 1">XII TKR 1</option>
                                        <option value="XII TKR 2">XII TKR 2</option>
                                        <option value="XII TKR 3">XII TKR 3</option>
                                        <option value="XII TKR 4">XII TKR 4</option>
                                        <option value="XII TKR 5">XII TKR 5</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required minlength="3">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Password minimal 3 karakter</p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required minlength="3">
                            </div>

                            <div>
                                <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Masuk</label>
                                <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                            </div>
                            

                        </div>

                        <div class="mt-6 flex justify-end space-x-2">
                            <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm transition-all duration-200" onclick="closeSiswaModal()">
                                <i class="fas fa-times text-xs mr-1"></i>Batal
                            </button>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm transition-all duration-200">
                                <i class="fas fa-save text-xs mr-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        async function downloadPdfFile(url, filename) {
            try {
                const response = await fetch(url, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: { 'Accept': 'application/pdf' }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengunduh file PDF');
                }

                const blob = await response.blob();
                const objectUrl = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = objectUrl;
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                link.remove();
                window.URL.revokeObjectURL(objectUrl);
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'PDF tidak dapat diunduh. Silakan coba lagi.',
                });
            }
        }

        function openSiswaModal() {
            document.getElementById('siswa-modal').classList.remove('hidden');
            document.getElementById('siswa-modal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSiswaModal() {
            document.getElementById('siswa-modal').classList.add('hidden');
            document.getElementById('siswa-modal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        
        // Show validation errors in modal if form submission fails
        @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            openSiswaModal();
        });
        @endif
        
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form when select changes
            const select = document.getElementById('kelas_filter');
            select.addEventListener('change', function() {
                this.closest('form').submit();
            });
            
            // Konfirmasi hapus dengan SweetAlert
            const deleteForms = document.querySelectorAll('.delete-form');
            deleteForms.forEach(form => {
                const deleteBtn = form.querySelector('.delete-btn');
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data siswa akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
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
    </script>
    @endpush
</x-app-layout>