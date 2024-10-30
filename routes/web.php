<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NguoiDungController;
use App\Http\Controllers\BangGiaController;
use App\Http\Controllers\DatHangController;
use App\Http\Controllers\BanDoController;

Route::get('/', [AuthController::class, 'index'])->name('index');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::get('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::prefix('nguoi-dung')->middleware('auth')->group(function () {
    Route::get('/', [NguoiDungController::class, 'index'])->name('nguoi-dung.index');
    Route::post('/store', [NguoiDungController::class, 'store'])->name('nguoi-dung.store');
});

Route::prefix('bang-gia')->middleware('auth')->group(function () {
    Route::get('/', [BangGiaController::class, 'index'])->name('bang-gia.index');
    Route::post('/store', [BangGiaController::class, 'store'])->name('bang-gia.store');
    Route::get('/find', [BangGiaController::class, 'findHangByMaOrTen'])->name('bang-gia.find');
});

Route::prefix('dat-hang')->middleware('auth')->group(function () {
    Route::get('/', [DatHangController::class, 'index'])->name('dat-hang.index');
    Route::post('/store', [DatHangController::class, 'store'])->name('dat-hang.store');
});

Route::prefix('map')->middleware('auth')->group(function () {
    Route::get('/', [BanDoController::class, 'index'])->name('map.index');
});




