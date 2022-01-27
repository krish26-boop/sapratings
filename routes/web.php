<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductManageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [ProductManageController::class, 'index'])->name('home');
Route::post('add-update-book', [ProductManageController::class, 'store']);
Route::post('edit-book', [ProductManageController::class, 'edit']);
Route::post('delete-book', [ProductManageController::class, 'destroy']);