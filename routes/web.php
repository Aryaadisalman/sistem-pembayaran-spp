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
    });
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::delete('users-bulk', [UserController::class, 'bulkDestroy'])->name('users.bulk-destroy');
    Route::resource('siswa', SiswaController::class);
    Route::get('siswa-export', [SiswaController::class, 'exportPDF'])->name('siswa.export');
    Route::post('siswa-migrate', [SiswaController::class, 'migrateClass'])->name('siswa.migrate');
    Route::resource('spp', SppController::class);
    
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

require __DIR__.'/auth.php';