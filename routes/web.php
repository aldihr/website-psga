<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\AuthController;

// Login Admin
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
    
Route::get('/', [BeritaController::class, 'beranda']);

Route::get('/tentang-psga', function () {
    return view('tentang');
})->name('tentang.psga');

Route::get('/visi-misi', function () {
    return view('visi-misi');
});

Route::prefix('admin/berita')
    ->name('berita.')
    ->controller(BeritaController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::patch('/{id}/arsipkan', 'arsipkan')->name('arsipkan');
        Route::delete('/{id}', 'hapus')->name('hapus');
        Route::get('/arsip', [BeritaController::class, 'arsip'])->name('arsip');
        Route::patch('/{id}/pulihkan', [BeritaController::class, 'pulihkan'])->name('pulihkan');
    });

// Halaman detail berita untuk pengunjung
Route::get('/berita/{slug}', [BeritaController::class, 'show'])
    ->name('berita.show');


Route::get('/pengaduan', [PengaduanController::class, 'index'])
    ->name('pengaduan.index');

Route::get('/pengaduan/create', [PengaduanController::class, 'create'])
    ->name('pengaduan.create');

Route::post('/pengaduan', [PengaduanController::class, 'store'])
    ->name('pengaduan.store');

Route::get('/pengaduan/admin', [PengaduanController::class, 'admin'])
    ->name('pengaduan.admin');

Route::get('/pengaduan/{id}/edit', [PengaduanController::class, 'edit'])
    ->name('pengaduan.edit');

Route::put('/pengaduan/{id}', [PengaduanController::class, 'update'])
    ->name('pengaduan.update');

Route::get('/tracking', [PengaduanController::class, 'tracking'])
    ->name('pengaduan.tracking');

Route::view('/kontak', 'kontak')->name('kontak');


Route::view('/admin/dashboard', 'admin.dashboard')
    ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/pengaduan/admin/export/excel', [
    PengaduanController::class,
    'exportExcel'
])->name('pengaduan.export.excel');

Route::get('/pengaduan/admin/export/pdf', [
    PengaduanController::class,
    'exportPdf'
])->name('pengaduan.export.pdf');