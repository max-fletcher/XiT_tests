<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'admin_home'])->name('admin.home');
Route::post('/users/toggle_verification_ajax', [App\Http\Controllers\HomeController::class, 'toggle_verification_ajax'])->name('users.toggle_verification_ajax');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('disallow_unverified_users')->name('home');
Route::get('/users/verification_pending', [App\Http\Controllers\HomeController::class, 'verification_pending'])->name('users.verification_pending');
