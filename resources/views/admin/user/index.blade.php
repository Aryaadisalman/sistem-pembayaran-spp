<x-app-layout>
    <div class="p-4 sm:ml-[240px]">
        <div class="p-4 border-2 border-gray-200 rounded-lg bg-white mt-14">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-base font-medium text-gray-800 mx-auto text-center">Manajemen Akun</h2>
            </div>

            @if(session('success'))
                <script>
                    Swal.fire({ title: "{{ session('success') }}", icon: "success", draggable: true });
                </script>
            @endif
            @if(session('error'))
                <script>
                    Swal.fire({ title: "{{ session('error') }}", icon: "error", draggable: true });
                </script>
            @endif
            
            <!-- Filter Section -->
            <div class="mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200 shadow-sm">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-2 flex-wrap">
                        <div>
                            <label for="role" class="block text-xs font-medium text-gray-500 mb-1">
                                <i class="fas fa-user-tag text-xs"></i> <span class="ml-1">Role:</span>
                            </label>
                            <select name="role" id="role" class="text-xs w-36 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5" onchange="this.form.submit()">
                                <option value="all" {{ request('role') == 'all' || !request('role') ? 'selected' : '' }}>Semua Role</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kepsek" {{ request('role') == 'kepsek' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                            </select>
                        </div>
                        <div>
                            <label for="kelas" class="block text-xs font-medium text-gray-500 mb-1">
                                <i class="fas fa-chalkboard text-xs"></i> <span class="ml-1">Kelas:</span>
                            </label>
                            <select name="kelas" id="kelas" class="text-xs w-36 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-500 mb-1">
                                <i class="fas fa-search text-xs"></i> <span class="ml-1">Nama:</span>
                            </label>
                            <input type="text" name="name" id="name" class="text-xs w-36 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-1.5" value="{{ request('name') }}" placeholder="Cari nama...">
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-2 rounded-md shadow-sm">
                                <i class="fas fa-filter text-xs"></i>
                            </button>
                        </div>
                        @if((request('role') && request('role') != 'all') || request('name') || request('kelas'))
                            <div class="mt-5">
                                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1 px-2 rounded-md shadow-sm">
                                    <i class="fas fa-times text-xs"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Bulk Delete Bar -->
            <div id="bulk-bar" class="hidden mb-3 p-2 bg-red-50 border border-red-200 rounded-lg flex items-center justify-between">
                <span class="text-xs text-red-700"><span id="selected-count">0</span> akun dipilih</span>
                <button type="button" id="bulk-delete-btn" class="bg-red-500 hover:bg-red-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm">
                    <i class="fas fa-trash text-xs mr-1"></i> Hapus yang Dipilih
                </button>
            </div>

            <!-- Bulk Delete Form -->
            <form id="bulk-delete-form" action="{{ route('admin.users.bulk-destroy') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
                <div id="bulk-inputs"></div>
            </form>
            
            <div class="overflow-x-auto relative shadow-sm rounded-lg border border-gray-200">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                        <tr>
                            <th scope="col" class="py-2 px-3">
                                <input type="checkbox" id="check-all" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            </th>
                            <th scope="col" class="py-2 px-4">No</th>
                            <th scope="col" class="py-2 px-4">Email</th>
                            <th scope="col" class="py-2 px-4">Nama</th>
                            <th scope="col" class="py-2 px-4">Role</th>
                            <th scope="col" class="py-2 px-4">Kelas</th>
                            <th scope="col" class="py-2 px-4">Status</th>
                            <th scope="col" class="py-2 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="bg-white border-b hover:bg-gray-50 text-sm">
                                <td class="py-2.5 px-3">
                                    <input type="checkbox" class="row-check rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" value="{{ $user->user_id }}">
                                </td>
                                <td class="py-2.5 px-4">{{ $index + 1 }}</td>
                                <td class="py-2.5 px-4">{{ $user->email }}</td>
                                <td class="py-2.5 px-4">{{ $user->nama }}</td>
                                <td class="py-2.5 px-4">
                                    <span class="px-2 py-0.5 rounded-sm text-xs font-medium
                                        @if($user->role === 'admin') bg-red-100 text-red-800
                                        @elseif($user->role === 'kepsek') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-4 text-xs">
                                    {{ $user->siswa->kelas ?? '-' }}
                                </td>
                                <td class="py-2.5 px-4">
                                    @if($user->role === 'siswa')
                                        @if($user->siswa && $user->siswa->is_aktif)
                                            <span class="px-2 py-0.5 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-sm text-xs font-medium bg-gray-100 text-gray-600">Lulus</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-0.5 rounded-sm text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                    @endif
                                </td>
                                <td class="py-2.5 px-4 flex space-x-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs p-1.5 rounded-md shadow-sm">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button" data-modal-target="user-modal-{{ $user->user_id }}" data-modal-toggle="user-modal-{{ $user->user_id }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs p-1.5 rounded-md shadow-sm">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white text-xs p-1.5 rounded-md shadow-sm delete-btn">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-white border-b">
                                <td class="py-4 px-6 text-center" colspan="8">Tidak ada data akun</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- User Detail Modals -->
    @foreach($users as $user)
    <div id="user-modal-{{ $user->user_id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 left-0 right-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-[80%] max-w-2xl max-h-full mx-auto">
            <div class="relative bg-white rounded-lg shadow-sm">
                <div class="flex items-center justify-between p-3 border-b rounded-t border-gray-200">
                    <h3 class="text-base font-medium text-gray-900">Detail Akun: {{ $user->nama }}</h3>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-500 rounded-lg text-sm w-6 h-6 ms-auto inline-flex justify-center items-center" data-modal-toggle="user-modal-{{ $user->user_id }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                    </button>
                </div>
                <div class="p-3 space-y-3">
                    <div class="bg-white rounded-md">
                        <h3 class="text-sm font-medium mb-2 px-2 py-1 bg-gray-50 border-b border-gray-100">Informasi Akun</h3>
                        <table class="w-full border border-gray-300">
                            <tbody>
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 font-medium text-gray-500 border-r border-gray-300">Nama</td>
                                    <td class="px-4 py-2">{{ $user->nama }}</td>
                                </tr>
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 font-medium text-gray-500 border-r border-gray-300">Email</td>
                                    <td class="px-4 py-2">{{ $user->email }}</td>
                                </tr>
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 font-medium text-gray-500 border-r border-gray-300">Role</td>
                                    <td class="px-4 py-2">
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                            @if($user->role === 'admin') bg-red-100 text-red-800
                                            @elseif($user->role === 'kepsek') bg-purple-100 text-purple-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @if($user->role === 'siswa' && $user->siswa)
                    <div class="bg-white rounded-md">
                        <h3 class="text-sm font-medium mb-2 px-2 py-1 bg-gray-50 border-b border-gray-100">Informasi Siswa</h3>
                        <table class="w-full border border-gray-300">
                            <tbody>
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 font-medium text-gray-500 border-r border-gray-300">NIS</td>
                                    <td class="px-4 py-2">{{ $user->siswa->nis }}</td>
                                </tr>
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 font-medium text-gray-500 border-r border-gray-300">Kelas</td>
                                    <td class="px-4 py-2">{{ $user->siswa->kelas }}</td>
                                </tr>
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 font-medium text-gray-500 border-r border-gray-300">Status</td>
                                    <td class="px-4 py-2">{{ $user->siswa->is_aktif ? 'Aktif' : 'Lulus' }}</td>
                                </tr>
                                <tr class="border border-gray-300">
                                    <td class="px-4 py-2 font-medium text-gray-500 border-r border-gray-300">Tanggal Masuk</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($user->siswa->tanggal_masuk)->format('d M Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
                <div class="flex items-center justify-center p-3 border-t border-gray-200 rounded-b space-x-2">
                    <button type="button" class="bg-gray-500 hover:bg-gray-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm" data-modal-toggle="user-modal-{{ $user->user_id }}">
                        <i class="fas fa-times text-xs mr-1"></i>Tutup
                    </button>
                    <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1.5 px-3 rounded-md shadow-sm">
                        <i class="fas fa-edit text-xs mr-1"></i>Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('check-all');
    const rowChecks = document.querySelectorAll('.row-check');
    const bulkBar = document.getElementById('bulk-bar');
    const selectedCount = document.getElementById('selected-count');
    const bulkInputs = document.getElementById('bulk-inputs');

    function updateBulkBar() {
        const checked = document.querySelectorAll('.row-check:checked');
        selectedCount.textContent = checked.length;
        bulkBar.classList.toggle('hidden', checked.length === 0);
    }

    checkAll.addEventListener('change', function() {
        rowChecks.forEach(cb => cb.checked = this.checked);
        updateBulkBar();
    });

    rowChecks.forEach(cb => {
        cb.addEventListener('change', function() {
            checkAll.checked = [...rowChecks].every(c => c.checked);
            updateBulkBar();
        });
    });

    document.getElementById('bulk-delete-btn').addEventListener('click', function() {
        const checked = document.querySelectorAll('.row-check:checked');
        if (checked.length === 0) return;

        Swal.fire({
            title: 'Hapus ' + checked.length + ' akun?',
            text: 'Akun yang dipilih akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                bulkInputs.innerHTML = '';
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'user_ids[]';
                    input.value = cb.value;
                    bulkInputs.appendChild(input);
                });
                document.getElementById('bulk-delete-form').submit();
            }
        });
    });

    // Single delete confirmation
    const deleteBtns = document.querySelectorAll('.delete-btn');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('.delete-form');
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Akun ini akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>