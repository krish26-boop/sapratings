<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExamManageController;

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

Route::get('/', [ExamManageController::class, 'index'])->name('home');
Route::post('add-update-question', [ExamManageController::class, 'store']);
Route::post('edit-question', [ExamManageController::class, 'edit']);
Route::post('delete-question', [ExamManageController::class, 'destroy']);