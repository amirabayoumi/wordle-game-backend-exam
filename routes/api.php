<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WordControllerApi;

use App\Http\Controllers\IconControllerApi;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });




    Route::get('/icons', [IconControllerApi::class, 'index']);

    Route::post('/icons', [IconControllerApi::class, 'store'])
        ->name('icons.store');
    Route::get('/icons/{icon}', [IconControllerApi::class, 'show'])
        ->name('icons.show');
    Route::put('/icons/{icon}', [IconControllerApi::class, 'update'])
        ->name('icons.update');
    Route::delete('/icons/{icon}', [IconControllerApi::class, 'destroy'])
        ->name('icons.destroy');
});


// game api routes
Route::post('/words/check', [WordControllerApi::class, 'check']);
