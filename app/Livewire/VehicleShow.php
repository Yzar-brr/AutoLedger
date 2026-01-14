<?php

namespace App\Livewire;

use App\Models\Vehicle;
use App\Models\VehiclePhoto;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VehicleShow extends Component
{
    use WithFileUploads;

    public Vehicle $vehicle;

    public $label;

    public $amount;

    public $notes;

    public $newPhotos = []; // Utilisé pour l'upload multiple

    public function mount(Vehicle $vehicle)
    {
        // On charge les relations pour éviter les erreurs de "undefined relationship"
        $this->vehicle = $vehicle->load(['expenses', 'photos']);
    }

    /**
     * Hook automatique : s'exécute dès que des photos sont sélectionnées
     */
    public function updatedNewPhotos()
    {
        $this->validate([
            'newPhotos.*' => 'image|max:3072', // 3Mo max
        ]);

        foreach ($this->newPhotos as $photo) {
            // STOCKAGE : On range dans le dossier spécifique de l'utilisateur
            $path = $photo->store('vehicles/'.auth()->id(), 'public');

            // INSERTION : On crée l'entrée en base de données
            $this->vehicle->photos()->create([
                'path' => $path,
            ]);
        }

        // Nettoyage de la propriété temporaire
        $this->newPhotos = [];

        // Rafraîchir le modèle pour afficher les nouvelles photos
        $this->vehicle->refresh();

        session()->flash('message', 'Photos ajoutées avec succès.');
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

    public function deletePhoto($photoId)
    {
        // On récupère la photo
        $photo = VehiclePhoto::findOrFail($photoId);

        // Suppression physique du fichier sur le disque
        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        // Suppression de la ligne en base de données
        $photo->delete();

        // Rafraîchir l'affichage
        $this->vehicle->refresh();
    }

    public function render()
    {
        return view('livewire.vehicle-show')->layout('layouts.app');
    }
}
