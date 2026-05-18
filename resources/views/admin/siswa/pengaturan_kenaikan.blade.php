<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">

            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                    Pengaturan Kenaikan Kelas
                </h2>
                <a href="{{ route('admin.siswa.index') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm transition-all duration-200 flex items-center">
                    <i class="fas fa-arrow-left text-xs mr-1"></i> Kembali
                </a>
            </div>

            {{-- Alert success --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    });
                </script>
            @endif

            {{-- Alert error --}}
            @if(session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: '{{ session('error') }}',
                        });
                    });
                </script>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Card 1: Pengaturan Bulan --}}
                <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-xl p-6">
                    <h3 class="text-base font-semibold text-blue-800 dark:text-blue-200 mb-1">
                        <i class="fas fa-calendar-alt mr-2"></i>Atur Bulan Kenaikan Otomatis
                    </h3>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mb-4">
                        Sistem akan otomatis menaikkan kelas setiap tahun pada bulan yang dipilih.
                        Saat ini: <strong>{{ $namaBulan[$bulanKenaikan] }}</strong>.
                    </p>

                    <form action="{{ route('admin.siswa.simpan-pengaturan-kenaikan') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="bulan_kenaikan_kelas"
                                   class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">
                                Bulan Kenaikan Kelas
                            </label>
                            <select name="bulan_kenaikan_kelas" id="bulan_kenaikan_kelas"
                                    class="w-full rounded-lg border-blue-300 dark:border-blue-600 dark:bg-gray-800 dark:text-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach($namaBulan as $angka => $nama)
                                    <option value="{{ $angka }}" {{ $bulanKenaikan == $angka ? 'selected' : '' }}>
                                        {{ $nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bulan_kenaikan_kelas')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition-all duration-200">
                            <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                        </button>
                    </form>
                </div>

                {{-- Card 2: Proses Manual --}}
                <div class="bg-orange-50 dark:bg-orange-900/30 border border-orange-200 dark:border-orange-700 rounded-xl p-6">
                    <h3 class="text-base font-semibold text-orange-800 dark:text-orange-200 mb-1">
                        <i class="fas fa-play-circle mr-2"></i>Proses Kenaikan Kelas Manual
                    </h3>
                    <p class="text-xs text-orange-600 dark:text-orange-400 mb-4">
                        Jalankan kenaikan kelas sekarang tanpa menunggu scheduler.
                        Pastikan semua siswa yang <strong>tidak naik kelas</strong> sudah ditandai lebih dulu.
                    </p>

                    <form action="{{ route('admin.siswa.proses-kenaikan-manual') }}" method="POST"
                          onsubmit="return konfirmasiProses()">
                        @csrf
                        <div class="mb-4">
                            <label for="tahun_ajaran"
                                   class="block text-sm font-medium text-orange-700 dark:text-orange-300 mb-1">
                                Tahun Ajaran Baru
                            </label>
                            <input type="text"
                                   name="tahun_ajaran"
                                   id="tahun_ajaran"
                                   placeholder="contoh: {{ date('Y') }}/{{ date('Y') + 1 }}"
                                   value="{{ date('Y') }}/{{ date('Y') + 1 }}"
                                   class="w-full rounded-lg border-orange-300 dark:border-orange-600 dark:bg-gray-800 dark:text-gray-200 text-sm focus:ring-orange-500 focus:border-orange-500">
                            @error('tahun_ajaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-orange-500 mt-1">Format: YYYY/YYYY, contoh: 2026/2027</p>
                        </div>

                        <button type="submit"
                                class="w-full bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium py-2 px-4 rounded-lg transition-all duration-200">
                            <i class="fas fa-graduation-cap mr-1"></i> Proses Kenaikan Sekarang
                        </button>
                    </form>
                </div>

            </div>

            {{-- Info box --}}
            <div class="mt-6 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl p-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-info-circle mr-1 text-blue-500"></i> Catatan Penting
                </h4>
                <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1 list-disc list-inside">
                    <li>Kenaikan <strong>otomatis</strong> berjalan setiap hari, tapi hanya mengeksekusi pada bulan yang dipilih.</li>
                    <li>Kenaikan <strong>manual</strong> bisa dijalankan kapan saja, namun setiap tahun ajaran hanya bisa diproses <strong>sekali</strong>.</li>
                    <li>Siswa kelas XII akan otomatis ditandai <strong>Lulus</strong> dan dinonaktifkan.</li>
                    <li>Siswa yang dicentang <strong>"Tidak Naik Kelas"</strong> di halaman edit akan tetap di kelas yang sama.</li>
                    <li>Tandai siswa yang tidak naik kelas di halaman <a href="{{ route('admin.siswa.index') }}" class="text-blue-500 underline">Data Siswa</a> sebelum memproses.</li>
                </ul>
            </div>

        </div>
    </div>

    <script>
        function konfirmasiProses() {
            const tahun = document.getElementById('tahun_ajaran').value;
            return confirm(
                'Proses kenaikan kelas untuk tahun ajaran ' + tahun + '?\n\n' +
                'Aksi ini tidak bisa dibatalkan. Pastikan semua siswa yang tidak naik kelas sudah ditandai.'
            );
        }
    </script>
</x-app-layout>