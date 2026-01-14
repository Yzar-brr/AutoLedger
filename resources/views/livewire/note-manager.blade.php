<div x-data="{ showForm: false }">
    {{-- Bouton pour déplier le formulaire --}}
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Mes Notes</h3>
        <button @click="showForm = !showForm" 
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-[10px] uppercase tracking-widest shadow-lg transition-all flex items-center">
            <svg x-show="!showForm" class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span x-text="showForm ? 'Fermer' : 'Nouvelle Note'"></span>
        </button>
    </div>

    {{-- Formulaire de prise de note (Replié par défaut) --}}
    <div x-show="showForm" x-collapse class="mb-10 bg-slate-50 p-6 rounded-3xl border border-slate-200">
        <form wire:submit.prevent="addNote" class="space-y-4">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Titre de la note</label>
                <input type="text" wire:model="title" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                @error('title') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Contenu</label>
                <textarea wire:model="content" rows="4" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm"></textarea>
                @error('content') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
            </div>
            <div class="flex justify-end">
                <button type="submit" @click="showForm = false" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-xl text-[10px] uppercase tracking-widest shadow-md transition">
                    Enregistrer la note
                </button>
            </div>
        </form>
    </div>

    {{-- Liste des notes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($notes as $note)
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 hover:border-indigo-100 transition group">
                <div class="flex justify-between items-start mb-2">
                    <h4 class="font-bold text-gray-900">{{ $note->title }}</h4>
                    <button wire:click="deleteNote({{ $note->id }})" class="text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $note->content }}</p>
                <p class="mt-4 text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $note->created_at->diffForHumans() }}</p>
            </div>
        @empty
            <div class="col-span-2 text-center py-12 border-2 border-dashed border-gray-100 rounded-3xl text-gray-300 font-bold uppercase text-xs">
                Aucune note pour le moment
            </div>
        @endforelse
    </div>
</div>