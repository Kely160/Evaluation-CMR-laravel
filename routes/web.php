<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\TauxAlerteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/checkLogin', [AuthController::class, 'checkLogin'])->name('verifier-connexion');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/details-budget', [DashboardController::class, 'detailsBudget'])->name('details-budget');
Route::get('/details/{type}', [DashboardController::class, 'detailsTotal'])->name('details-total');
Route::get('/details/{type}/{month}', [DashboardController::class, 'details'])->name('details');
Route::delete('/details/{type}/{id}', [DashboardController::class, 'destroy'])->name('details.destroy');

Route::post('/depense', [DepenseController::class, 'update'])->name('update-depense');

Route::get('/tauxAlerte', [TauxAlerteController::class, 'index'])->name('formulaire-modification-taux');
Route::post('/tauxAlerte', [TauxAlerteController::class, 'create'])->name('update-taux');



