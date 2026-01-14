<?php

use App\Livewire\VehicleList;
use App\Livewire\VehicleShow;
use App\Models\Truck;
use Illuminate\Support\Facades\Route;
use App\Models\MaintenancePlan;
use Barryvdh\DomPDF\Facade\Pdf;

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
    if ($client->user_id !== auth()->id()) {
        abort(403);
    }

    return view('client-detail', ['client' => $client]);
})->name('client.show')->middleware(['auth']);

Route::get('/truck/{truck}', function (Truck $truck) {
    if ($truck->client->user_id !== auth()->id()) {
        abort(403);
    }

    return view('truck-detail', ['truck' => $truck]);
})->name('truck.show')->middleware(['auth']);

Route::get('/maintenance-plan/{plan}/pdf', function (MaintenancePlan $plan) {
    $pdf = Pdf::loadView('pdf.maintenance-report', compact('plan'));
    
    $filename = 'rapport_' . $plan->truck->plate_number . '_' . $plan->check_date->format('d-m-Y') . '.pdf';
    
    return $pdf->download($filename);
})->name('maintenance.pdf');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/dashboard', VehicleList::class)->name('dashboard');
Route::get('/vehicles/{vehicle}', VehicleShow::class)->name('vehicles.show');
require __DIR__.'/auth.php';
