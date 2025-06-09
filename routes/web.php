<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WordController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\IconController;
use App\Models\Icon;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('words', WordController::class)
        ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])
        ->names('words');
    Route::get('/players', [PlayerController::class, 'index'])->name('players.index');
    Route::get('/players/{id}', [PlayerController::class, 'show'])->name('players.show');
    //icons
    Route::get('/icons', [IconController::class, 'index'])->name('icons.index');
    Route::get('/icons/create', [IconController::class, 'create'])->name('icons.create');
    Route::post('/icons', [IconController::class, 'store'])->name('icons.store');
    Route::get('/icons/{icon}/edit', [IconController::class, 'edit'])->name('icons.edit');
    Route::put('/icons/{icon}', [IconController::class, 'update'])->name('icons.update');
    Route::delete('/icons/{icon}', [IconController::class, 'destroy'])->name('icons.destroy');



    Route::get('/test-game', function () {
        return view('testGame');
    })->name('testGame');
});
