<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;

class ClientManager extends Component
{
    public $name;

    public $phone;

    public $contact_person;

    public $showForm = false; // Pour déplier le formulaire

    public function addClient()
    {
        $this->validate([
            'name' => 'required|min:2',
            'phone' => 'nullable|string',
        ]);

        Client::create([
            'user_id' => auth()->id(), // On lie le client à toi
            'name' => $this->name,
            'phone' => $this->phone,
            'contact_person' => $this->contact_person,
        ]);

        $this->reset(['name', 'phone', 'contact_person', 'showForm']);
        session()->flash('message', 'Client ajouté !');
    }

    public function deleteClient($id)
    {
        $client = \App\Models\Client::findOrFail($id);

        // Si tu as des photos liées aux interventions des camions de ce client,
        // il faudrait idéalement les supprimer du stockage ici.

        $client->delete();

        // Notification ou message flash (optionnel)
        session()->flash('message', 'Client supprimé avec succès.');
    }

    public function render()
    {
        return view('livewire.client-manager', [
            // On récupère tes clients avec leurs camions pour afficher le compteur
            'clients' => Client::where('user_id', auth()->id())->withCount('trucks')->latest()->get(),
        ]);
    }
}
