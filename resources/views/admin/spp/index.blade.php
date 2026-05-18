<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 mt-14">
            <div class="p-0 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-t-lg flex items-center justify-center border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-base font-medium">Data SPP, PPDB & DU</h3>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-1">
                            <a href="{{ route('admin.spp.index', ['jenis' => 'spp']) }}" 
                               class="px-3 py-1.5 rounded-md text-xs shadow-sm {{ request('jenis') === 'spp' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} hover:bg-blue-600 hover:text-white transition-all duration-200 flex items-center">
                                <span>SPP ({{ $sppCount }})</span>
                            </a>
                            <a href="{{ route('admin.spp.index', ['jenis' => 'ppdb']) }}" 
                               class="px-3 py-1.5 rounded-md text-xs shadow-sm {{ request('jenis') === 'ppdb' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} hover:bg-blue-600 hover:text-white transition-all duration-200 flex items-center">
                                <span>PPDB ({{ $ppdbCount }})</span>
                            </a>
                            <a href="{{ route('admin.spp.index', ['jenis' => 'du']) }}" 
                               class="px-3 py-1.5 rounded-md text-xs shadow-sm {{ request('jenis') === 'du' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700' }} hover:bg-blue-600 hover:text-white transition-all duration-200 flex items-center">
                                <span>DU ({{ $duCount }})</span>
                            </a>
                            @if(request('jenis'))
                                <a href="{{ route('admin.spp.index') }}" 
                                   class="px-3 py-1.5 rounded-md text-xs shadow-sm bg-red-500 text-white hover:bg-red-600 transition-all duration-200 flex items-center">
                                    <i class="fas fa-times text-xs"></i>
                                    <span class="ml-1">Reset</span>
                                </a>
                            @endif
                        </div>
                        <div class="flex space-x-2 justify-end">
                            <a href="{{ route('admin.spp.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1.5 px-2.5 rounded-md shadow-sm transition-all duration-200 flex items-center">
                                <i class="fas fa-plus text-xs"></i>
                                <span class="ml-1">Tambah</span>
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    </script>
                    @endif

                    @if(session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: '{{ session('error') }}',
                            showConfirmButton: true,
                        });
                    </script>
                    @endif

                    <div class="overflow-x-auto mt-3">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-2 px-4">No</th>
                                    <th scope="col" class="py-2 px-4">Nama</th>
                                    <th scope="col" class="py-2 px-4">Jenis</th>
                                    <th scope="col" class="py-2 px-4">Tahun</th>
                                    <th scope="col" class="py-2 px-4">Nominal</th>
                                    <th scope="col" class="py-2 px-4">Max Angsuran</th>
                                    <th scope="col" class="py-2 px-4">Status</th>
                                    <th scope="col" class="py-2 px-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spp as $index => $item)
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-sm">
                                    <td class="py-2.5 px-4">{{ $index + 1 }}</td>
                                    <td class="py-2.5 px-4">{{ $item->nama }}</td>
                                    <td class="py-2.5 px-4">
                                        @if($item->jenis === 'spp')
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">SPP</span>
                                        @elseif($item->jenis === 'ppdb')
                                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-0.5 rounded">PPDB</span>
                                        @else
                                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5 rounded">DU</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4">{{ $item->tahun_ajaran }}</td>
                                    <td class="py-2.5 px-4">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                                    <td class="py-2.5 px-4">
                                        @if($item->jenis === 'du' && $item->max_angsuran)
                                            <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-0.5 rounded">{{ $item->max_angsuran }}x</span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4">
                                        @if($item->is_aktif)
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-sm dark:bg-blue-900 dark:text-blue-300">Aktif</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded-sm dark:bg-red-900 dark:text-red-300">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-4 flex space-x-1">
                                        <a href="{{ route('admin.spp.edit', $item->spp_id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs p-1.5 rounded-md shadow-sm transition-all duration-200">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('admin.spp.destroy', $item->spp_id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs p-1.5 rounded-md shadow-sm transition-all duration-200 delete-confirm">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="py-4 px-6 text-center" colspan="8">Tidak ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $spp->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-confirm');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.closest('form').submit();
                        }
                    });
                });
            });
        });
    </script>
    @endpush
</x-app-layout>