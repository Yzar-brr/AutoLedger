<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Vehicle;
use App\Models\Expense;
use App\Models\VehiclePhoto;
use Illuminate\Support\Facades\Storage;

class VehicleShow extends Component
{
    use WithFileUploads;

    public Vehicle $vehicle;
    public $label, $amount, $notes;
    public $newPhotos = [];

    public function mount(Vehicle $vehicle)
    {
        // On charge les relations pour éviter les erreurs
        $this->vehicle = $vehicle->load(['expenses', 'photos']);
    }

    public function addExpense()
    {
        $this->validate([
            'label' => 'required|min:3',
            'amount' => 'required|numeric',
        ]);

        $this->vehicle->expenses()->create([
            'label' => $this->label,
            'amount' => $this->amount,
            'notes' => $this->notes,
        ]);

        $this->reset(['label', 'amount', 'notes']);
        $this->vehicle->refresh();
    }

    public function uploadPhotos()
    {
        $this->validate(['newPhotos.*' => 'image|max:3072']);

        foreach ($this->newPhotos as $photo) {
            $path = $photo->store('vehicles/gallery', 'public');
            $this->vehicle->photos()->create(['path' => $path]);
        }

        $this->newPhotos = [];
        $this->vehicle->refresh();
    }

    public function deletePhoto($photoId)
    {
        $photo = VehiclePhoto::findOrFail($photoId);
        Storage::disk('public')->delete($photo->path);
        $photo->delete();
        $this->vehicle->refresh();
    }

    public function render()
    {
        // On force le layout standard pour garder les bords gris
        return view('livewire.vehicle-show')->layout('layouts.app');
    }
}