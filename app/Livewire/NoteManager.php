<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class NoteManager extends Component
{
    public $title;

    public $content;

    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required|min:5',
    ];

    public function addNote()
    {
        $this->validate();

        auth()->user()->notes()->create([
            'title' => $this->title,
            'content' => $this->content,
        ]);

        $this->reset(['title', 'content']);
        session()->flash('message', 'Note ajoutée avec succès !');
    }

    public function deleteNote($id)
    {
        Note::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public function render()
    {
        return view('livewire.note-manager', [
            'notes' => auth()->user()->notes()->latest()->get(),
        ]);
    }
}
