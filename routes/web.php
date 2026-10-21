<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MasterHargaController;
use App\Http\Controllers\SupplierController;
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
Route::get('/masterharga', [MasterHargaController::class, 'index'])->name('masterharga.index');

Route::post('/masterharga/store', [MasterHargaController::class, 'store'])->name('masterharga.price.store');
Route::delete('/masterharga/{id}', [MasterHargaController::class, 'destroy'])->name('masterharga.price.destroy');

Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');
Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
require __DIR__.'/auth.php';