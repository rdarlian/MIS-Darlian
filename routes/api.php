<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BarangController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::name('auth.')->group(function () {
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::post('register', [UserController::class, 'register'])->name('register');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [UserController::class, 'logout'])->name('logout');
        Route::get('user', [UserController::class, 'fetch'])->name('fetch');
        Route::post('user/update/{id}', [UserController::class, 'update'])->name('update');
        Route::get('userall', [UserController::class, 'fetchAll'])->name('fetchall');
    });
});

//barang API
Route::prefix('barang')->middleware('auth:sanctum')->name('barang.')->group(function () {
    Route::get('', [BarangController::class, 'fetch'])->name('fetch');
    Route::post('', [BarangController::class, 'create'])->name('create');
    Route::post('update/{id}', [BarangController::class, 'update'])->name('update');
    Route::delete('{id}', [BarangController::class, 'destroy'])->name('delete');
});
