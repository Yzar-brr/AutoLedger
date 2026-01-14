<?php

namespace App\Livewire;

use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleList extends Component
{
    use WithFileUploads;

    public $isOpen = false;

    public $model;

    public $registration;

    public $status = 'disponible';

    public $image;

    // NOUVELLES PROPRIÉTÉS À AJOUTER
    public $purchase_price;

    public $selling_price;

    public function openModal()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->model = '';
        $this->registration = '';
        $this->status = 'disponible';
        $this->image = null;
        $this->purchase_price = null; // Reset prix
        $this->selling_price = null;  // Reset prix
    }

    public function save()
    {
        $this->validate([
            'model' => 'required|min:2',
            'registration' => [
                'required',
                'regex:/^([A-Z]{2}-\d{3}-[A-Z]{2})|(\d{1,4}\s[A-Z]{1,3}\s\d{2,3})$/',
            ],
            'status' => 'required',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ], [
            'registration.regex' => 'Format invalide (Ex: AB-123-CD ou 1234 AB 56).',
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('vehicles/'.auth()->id(), 'public');
        }

        // Création du véhicule AVEC user_id
        Vehicle::create([
            'user_id' => auth()->id(), // <--- INDISPENSABLE car tu as ajouté la colonne
            'model' => $this->model,
            'registration' => strtoupper($this->registration),
            'status' => $this->status,
            'purchase_price' => $this->purchase_price ?: 0,
            'selling_price' => $this->selling_price ?: 0,
            'image_path' => $imagePath,
        ]);

        // Reset et Feedback
        $this->reset(['model', 'registration', 'status', 'purchase_price', 'selling_price', 'image']);

        if (method_exists($this, 'closeModal')) {
            $this->closeModal();
        }

        session()->flash('message', 'Véhicule ajouté avec succès.');
    }

    public function render()
    {
        return view('livewire.vehicle-list', [
            // On s'assure que la clé du tableau est bien 'vehicles'
            'vehicles' => Vehicle::where('user_id', auth()->id())
                ->latest()
                ->get(),
        ])->layout('layouts.app');
    }
}
