<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Truck;
use App\Models\Intervention;

class TruckInterventions extends Component
{
    use WithFileUploads;

    public Truck $truck; // On reçoit le camion actuel
    
    // Champs du formulaire
    public $date;
    public $technician;
    public $price;
    public $duration;
    public $description;
    public $newPhotos = []; // Pour l'upload

    public function mount(Truck $truck)
    {
        $this->truck = $truck;
        $this->date = now()->format('Y-m-d'); // Date d'aujourd'hui par défaut
    }

    public function addIntervention()
    {
        $this->validate([
            'date' => 'required|date',
            'technician' => 'required|string',
            'price' => 'required|numeric',
            'duration' => 'required|string',
            'description' => 'required|string',
            'newPhotos.*' => 'image|max:5000', // 5Mo max
        ]);

        // Gestion des images
        $photoPaths = [];
        foreach ($this->newPhotos as $photo) {
            $photoPaths[] = $photo->store('interventions/' . $this->truck->id, 'public');
        }

        $this->truck->interventions()->create([
            'date' => $this->date,
            'technician' => $this->technician,
            'price' => $this->price,
            'duration' => $this->duration,
            'description' => $this->description,
            'photos' => $photoPaths,
        ]);

        $this->reset(['technician', 'price', 'duration', 'description', 'newPhotos']);
        session()->flash('message', 'Intervention ajoutée !');
    }

    public function deleteIntervention($id)
    {
        Intervention::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.truck-interventions', [
            'interventions' => $this->truck->interventions()->latest()->get()
        ]);
    }
}