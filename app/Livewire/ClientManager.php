<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Client;

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

    public function render()
    {
        return view('livewire.client-manager', [
            // On récupère tes clients avec leurs camions pour afficher le compteur
            'clients' => Client::where('user_id', auth()->id())->withCount('trucks')->latest()->get()
        ]);
    }
}