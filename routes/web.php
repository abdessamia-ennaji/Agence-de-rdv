<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RdvController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    
    Route::get('/rdv/create/step1', [RdvController::class, 'createStep1'])->name('rdv.createStep1');
    Route::post('/rdv/storeStep1', [RdvController::class, 'storeStep1'])->name('rdv.storeStep1');

    Route::get('/rdv/create/step2', [RdvController::class, 'createStep2'])->name('rdv.createStep2');
    Route::post('/rdv/storeStep2', [RdvController::class, 'storeStep2'])->name('rdv.storeStep2');

    Route::get('/rdv/create/review', [RdvController::class, 'createReview'])->name('rdv.createReview');
    Route::post('/rdv/store', [RdvController::class, 'store'])->name('rdv.store');

    Route::get('/rdv', [RdvController::class, 'show'])->name('rdv.show');



    // Route::get('')
    Route::get('/rdv/{id}/edit', [RdvController::class, 'edit'])->name('edit'); 
    Route::put('/rdv/{id}/update', [RdvController::class, 'update'])->name('rdv.update');
    Route::delete('/rdv/{id}', [RdvController::class, 'destroy'])->name('rdv.destroy');

});


