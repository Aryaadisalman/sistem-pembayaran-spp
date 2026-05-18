<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\SppController;
use App\Http\Controllers\Admin\PembayaranController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\DashboardSiswaController;
use App\Http\Controllers\PembayaranSiswaController;
use App\Http\Controllers\ReceiptController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('splash');
});

Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin' || Auth::user()->role === 'kepsek') {
        return redirect()->route('admin.dashboard');
    }
    
    return app(DashboardSiswaController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/pembayaran/create', [PembayaranSiswaController::class, 'create'])->name('pembayaran.create');
        Route::post('/pembayaran', [PembayaranSiswaController::class, 'store'])->name('pembayaran.store');
        Route::get('/pembayaran', [PembayaranSiswaController::class, 'history'])->name('pembayaran.history');
        Route::get('/pembayaran/history', [PembayaranSiswaController::class, 'history'])->name('pembayaran.history');
        Route::get('/pembayaran/{id}/detail', [PembayaranSiswaController::class, 'getDetail'])->name('pembayaran.detail');
        Route::get('/pembayaran/{id}/receipt', [ReceiptController::class, 'generatePdf'])->name('pembayaran.receipt');

        // Routes Angsuran DU untuk siswa
        Route::get('/angsuran-du', [\App\Http\Controllers\AngsuranDuController::class, 'index'])->name('siswa.angsuran.du.index');
        Route::get('/angsuran-du/{spp_id}/bayar', [\App\Http\Controllers\AngsuranDuController::class, 'create'])->name('siswa.angsuran.du.create');
        Route::post('/angsuran-du/{spp_id}/bayar', [\App\Http\Controllers\AngsuranDuController::class, 'store'])->name('siswa.angsuran.du.store');
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::delete('users-bulk', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::resource('siswa', SiswaController::class);
    Route::get('siswa-export', [SiswaController::class, 'exportPDF'])->name('siswa.export');
    Route::get('siswa-template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::post('siswa-import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::post('siswa-migrate', [SiswaController::class, 'migrateClass'])->name('siswa.migrate');
    Route::patch('siswa/{id}/toggle-tidak-naik', [SiswaController::class, 'toggleTidakNaik'])->name('siswa.toggle-tidak-naik');
    Route::get('siswa-pengaturan-kenaikan', [SiswaController::class, 'pengaturanKenaikan'])->name('siswa.pengaturan-kenaikan');
    Route::post('siswa-pengaturan-kenaikan', [SiswaController::class, 'simpanPengaturanKenaikan'])->name('siswa.simpan-pengaturan-kenaikan');
    Route::post('siswa-proses-kenaikan-manual', [SiswaController::class, 'prosesKenaikanManual'])->name('siswa.proses-kenaikan-manual');
    Route::resource('spp', SppController::class);

    // Routes Angsuran DU untuk admin
    Route::get('angsuran-du', [\App\Http\Controllers\AngsuranDuController::class, 'adminIndex'])->name('angsuran.du.index');
    Route::post('angsuran-du/{angsuran_id}/verify', [\App\Http\Controllers\AngsuranDuController::class, 'verify'])->name('angsuran.du.verify');
    
    Route::resource('pembayaran', PembayaranController::class);
    Route::get('pembayaran/kelas/all', [PembayaranController::class, 'getAllSiswa'])->name('pembayaran.all-siswa');
    Route::get('pembayaran/kelas/{kelas}', [PembayaranController::class, 'getSiswaByKelas'])->name('pembayaran.siswa-by-kelas');
    Route::get('pembayaran/{id}/items', [PembayaranController::class, 'getItems'])->name('pembayaran.items');
    
    // Laporan routes
    Route::get('laporan/pembayaran', [LaporanController::class, 'pembayaran'])->name('laporan.pembayaran');
    Route::get('laporan/siswa-lunas', [LaporanController::class, 'siswaLunas'])->name('laporan.siswa-lunas');
    Route::get('laporan/siswa-menunggak', [LaporanController::class, 'siswaMenunggak'])->name('laporan.siswa-menunggak');
});

Route::match(['PUT', 'PATCH'], '/admin/pembayaran/update-status/{id}', [PembayaranController::class, 'updateStatus'])->middleware(['auth', 'verified', 'admin'])->name('admin.pembayaran.updateStatus');
// Route untuk serve bukti pembayaran langsung
Route::get('/bukti-pembayaran/{filename}', [\App\Http\Controllers\BuktiController::class, 'show'])
    ->middleware(['auth'])
    ->name('bukti.show');
// AJAX check nama duplikat saat registrasi
Route::post('/check-nama', function (\Illuminate\Http\Request $request) {
    $exists = \App\Models\Siswa::whereRaw('LOWER(nama) = ?', [strtolower(trim($request->nama))])->exists();
    return response()->json(['exists' => $exists]);
})->name('check.nama');
require __DIR__.'/auth.php';