<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\VehicleShow;
use App\Livewire\VehicleList; // Ton tableau de bord

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

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');



Route::get('/dashboard', VehicleList::class)->name('dashboard');
// {vehicle} permet à Laravel de comprendre qu'il doit chercher un modèle Vehicle
Route::get('/vehicles/{vehicle}', VehicleShow::class)->name('vehicles.show');
require __DIR__.'/auth.php';
