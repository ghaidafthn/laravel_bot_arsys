<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostingController;
use App\Http\Controllers\ArsysResearchController;

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
Route::resource('posting', PostingController::class);

// routes/web.php



Route::get('/arsys-research', [ArsysResearchController::class, 'index'])->name('arsys-research.index');
Route::get('/arsys-research/{id}', [ArsysResearchController::class, 'show'])->name('arsys-research.show');
