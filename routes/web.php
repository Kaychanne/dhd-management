<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MasterHargaController;
use App\Http\Controllers\PembelianStokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\StokGudangController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/inventori', [MaterialController::class, 'index'])->name('inventori.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::resource('materials', MaterialController::class);


Route::get('/masterharga', [MasterHargaController::class, 'index'])
    ->name('masterharga.index');

Route::get('/masterharga/create', [MasterHargaController::class, 'create'])
    ->name('masterharga.create');

Route::post('/masterharga/price', [MasterHargaController::class, 'store'])
    ->name('masterharga.price.store');

Route::get('/masterharga/price/{id}/edit', [MasterHargaController::class, 'edit'])
    ->name('masterharga.price.edit');

Route::put('/masterharga/price/{id}', [MasterHargaController::class, 'update'])
    ->name('masterharga.price.update');

Route::delete('/masterharga/price/{id}', [MasterHargaController::class, 'destroy'])
    ->name('masterharga.price.destroy');

Route::get('/supplier', [SupplierController::class, 'index'])
    ->name('supplier.index');

Route::get('/supplier/create', [SupplierController::class, 'create'])
    ->name('supplier.create');

Route::post('/supplier/store', [SupplierController::class, 'store'])
    ->name('supplier.store');

Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])
    ->name('supplier.edit');

Route::put('/supplier/{id}', [SupplierController::class, 'update'])
    ->name('supplier.update');

Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])
    ->name('supplier.destroy');

Route::get('/pembelianstok', [PembelianStokController::class, 'index'])->name('pembelianstok.index');
Route::get('/pembelianstok/create', [PembelianStokController::class, 'create'])->name('pembelianstok.create');
Route::post('/pembelianstok', [PembelianStokController::class, 'store'])->name('pembelianstok.store');
Route::get('/pembelian-stok/{id}/edit', [PembelianStokController::class, 'edit'])->name('pembelianstok.edit');
Route::put('/pembelian-stok/{id}', [PembelianStokController::class, 'update'])->name('pembelianstok.update');
Route::delete('/pembelianstok/{id}', [PembelianStokController::class, 'destroy'])->name('pembelianstok.destroy');

Route::resource('stokgudang', StokGudangController::class);
Route::get('/stok-gudang', [StokGudangController::class, 'index'])
    ->name('stokgudang.index');

Route::get('/stok-gudang/create', [StokGudangController::class, 'create'])
    ->name('stokgudang.create');

Route::post('/stok-gudang/store', [StokGudangController::class, 'store'])
    ->name('stokgudang.store');

Route::get('/stokgudang/{id}/edit', [StokGudangController::class, 'edit'])
    ->name('stokgudang.edit');

Route::delete('/stok-gudang/{id}', [StokGudangController::class, 'destroy'])
    ->name('stokgudang.destroy');


Route::get('/laporan', [LaporanController::class, 'index'])
    ->name('laporan.index');

Route::get('/laporan/preview', [LaporanController::class, 'preview'])
    ->name('laporan.preview');

Route::get('/laporan/export/csv', [LaporanController::class, 'exportCsv'])
    ->name('laporan.export.csv');
require __DIR__.'/auth.php';