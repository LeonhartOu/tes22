<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InputController;

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

Route::get('/', [InputController::class, 'viewList'])->name('index');
Route::get('/indexData', [InputController::class, 'getData'])->name('indexData'); // Get Data
Route::post('/saveData', [InputController::class, 'insertData'])->name('saveData'); // Insert Data

Route::get('/detailData/{id}', [InputController::class, 'getDetailData'])->name(''); // LIST DETAIL
Route::post('/saveEdit/{id}', [InputController::class, 'saveChangesData'])->name('saveEdit'); // LIST DETAIL
Route::delete('/deleteData/{id}', [InputController::class, 'deleteData'])->name('deleteData');

