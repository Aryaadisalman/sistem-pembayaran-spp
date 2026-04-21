<x-app-layout>
    <div class="p-4 sm:ml-[200px]">
        <div class="p-4 border-2 border-gray-200 rounded-lg bg-white mt-14">

        <div class="p-0 text-gray-900">
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

    <!-- PEMBAYARAN LUNAS -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'lunas']) }}" class="bg-green-500 p-4 rounded-lg flex justify-between items-center hover:cursor-pointer">
    @else
      <div class="bg-green-500 p-4 rounded-lg flex justify-between items-center hover:cursor-not-allowed" title="Anda Tidak Memiliki Akses">
    @endif
        <div>
          <p class="text-sm text-green-200">PEMBAYARAN LUNAS</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'lunas')->count() }}</p>
        </div>
        <i class="fa fa-check text-2xl text-green-200"></i>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- MENUNGGU VALIDASI -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'pending']) }}" class="bg-yellow-500 p-4 rounded-lg flex justify-between items-center hover:cursor-pointer">
    @else
      <div class="bg-yellow-500 p-4 rounded-lg flex justify-between items-center hover:cursor-not-allowed" title="Anda Tidak Memiliki Akses">
    @endif
        <div>
          <p class="text-sm text-yellow-200">MENUNGGU VALIDASI</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'pending')->count() }}</p>
        </div>
        <i class="fas fa-ticket-alt text-2xl text-yellow-200"></i>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- TOTAL -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'ditolak']) }}" class="bg-red-500 p-4 rounded-lg flex justify-between items-center hover:cursor-pointer">
    @else
      <div class="bg-red-500 p-4 rounded-lg flex justify-between items-center hover:cursor-not-allowed" title="Anda Tidak Memiliki Akses">
    @endif
        <div>
          <p class="text-sm text-red-200">Pembayaran DI Tolak</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'ditolak')->count() }}</p>
        </div>
        <i class="fas fa-ticket-alt text-2xl text-red-200"></i>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- TOTAL SEMUA SISWA AKTIF -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.siswa.index') }}" class="bg-blue-500 p-4 rounded-lg flex justify-between items-center hover:cursor-pointer">
    @else
      <div class="bg-blue-500 p-4 rounded-lg flex justify-between items-center hover:cursor-not-allowed" title="Anda Tidak Memiliki Akses">
    @endif
        <div>
          <p class="text-sm text-blue-200">TOTAL SEMUA SISWA</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Siswa::where('is_aktif', true)->count() }}</p>
        </div>
        <i class="fas fa-user text-2xl text-blue-200"></i>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- SISWA LUNAS -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'lunas']) }}" class="bg-purple-500 p-4 rounded-lg flex justify-between items-center hover:cursor-pointer">
    @else
      <div class="bg-purple-500 p-4 rounded-lg flex justify-between items-center hover:cursor-not-allowed" title="Anda Tidak Memiliki Akses">
    @endif
        <div>
          <p class="text-sm text-purple-200">SISWA LUNAS</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'lunas')->count() }}</p>
        </div>
        <i class="fas fa-user text-2xl text-purple-200"></i>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

    <!-- SISWA MENUNGGAK -->
    @if(Auth::user()->role === 'admin')
      <a href="{{ route('admin.pembayaran.index', ['status' => 'belum_bayar']) }}" class="bg-indigo-500 p-4 rounded-lg flex justify-between items-center hover:cursor-pointer">
    @else
      <div class="bg-indigo-500 p-4 rounded-lg flex justify-between items-center hover:cursor-not-allowed" title="Anda Tidak Memiliki Akses">
    @endif
        <div>
          <p class="text-sm text-indigo-200">SISWA MENUNGGAK</p>
          <p class="text-4xl font-bold text-white">{{ \App\Models\Pembayaran::where('status_pembayaran', 'belum_bayar')->count() }}</p>
        </div>
        <i class="fas fa-user-times text-2xl text-indigo-200"></i>
    @if(Auth::user()->role === 'admin')
      </a>
    @else
      </div>
    @endif

  </div>
</div>

        </div>
    </div>
</x-app-layout>

