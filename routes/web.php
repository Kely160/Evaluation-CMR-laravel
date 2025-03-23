<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/checkLogin', [AuthController::class, 'checkLogin'])->name('verifier-connexion');

Route::get('/template', function () {
    return view('template');
})->name('template');

