<?php

use App\Livewire\VehicleList;
use App\Livewire\VehicleShow;
use App\Models\Truck; // Ton tableau de bord
use Illuminate\Support\Facades\Route;

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

Route::view('notes', 'notes')
    ->middleware(['auth', 'verified'])
    ->name('notes');

Route::get('/client/{client}', function (App\Models\Client $client) {
    // Vérification de sécurité : le client doit t'appartenir
    if ($client->user_id !== auth()->id()) {
        abort(403);
    }

    return view('client-detail', ['client' => $client]);
})->name('client.show')->middleware(['auth']);

Route::get('/truck/{truck}', function (Truck $truck) {
    // Optionnel : vérifier que le camion appartient bien à un client de l'utilisateur connecté
    if ($truck->client->user_id !== auth()->id()) {
        abort(403);
    }

    return view('truck-detail', ['truck' => $truck]);
})->name('truck.show')->middleware(['auth']);

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/dashboard', VehicleList::class)->name('dashboard');
// {vehicle} permet à Laravel de comprendre qu'il doit chercher un modèle Vehicle
Route::get('/vehicles/{vehicle}', VehicleShow::class)->name('vehicles.show');
require __DIR__.'/auth.php';
