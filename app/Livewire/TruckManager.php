<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;
use App\Models\Truck;

class TruckManager extends Component
{
    public Client $client;
    public $brand;
    public $plate_number;
    public $showForm = false;

    public function addTruck()
    {
        $this->validate([
            'brand' => 'required',
            'plate_number' => 'required|unique:trucks,plate_number',
        ]);

        $this->client->trucks()->create([
            'brand' => $this->brand,
            'plate_number' => $this->plate_number,
        ]);

        $this->reset(['brand', 'plate_number', 'showForm']);
    }

    public function deleteTruck($id)
{
    $truck = Truck::where('id', $id)->first();
    
    // Sécurité : on vérifie que le camion appartient bien à un client de l'utilisateur
    if ($truck && $truck->client->user_id === auth()->id()) {
        $truck->delete();
    }
}


    public function render()
    {
        return view('livewire.truck-manager', [
            'trucks' => $this->client->trucks()->withCount('interventions')->latest()->get()
        ]);
    }
}