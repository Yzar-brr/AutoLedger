<div x-data="{ showForm: @entangle('showForm') }">
    
    {{-- En-tête + Bouton Ajouter --}}
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h3 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Mes Clients</h3>
            <p class="text-sm text-gray-500">Sélectionnez un client pour gérer sa flotte.</p>
        </div>
        <button @click="showForm = !showForm" 
        class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white border border-transparent rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200/50 transition-all active:scale-95">
    
    <svg x-show="!showForm" class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
    </svg>
    
    <svg x-show="showForm" class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
    </svg>

    <span x-text="showForm ? 'Fermer' : 'Nouveau Client'"></span>
</button>
    </div>

    {{-- Formulaire d'ajout (Dépliable) --}}
    <div x-show="showForm" x-collapse class="mb-10 bg-indigo-50 p-8 rounded-3xl border border-indigo-100">
        <form wire:submit.prevent="addClient" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nom Société / Client</label>
                <input type="text" wire:model="name" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 font-bold">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Contact (Nom)</label>
                <input type="text" wire:model="contact_person" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Téléphone</label>
                <input type="text" wire:model="phone" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500">
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl text-xs uppercase tracking-widest shadow-md transition">
                    Enregistrer le client
                </button>
            </div>
        </form>
    </div>

    {{-- Liste des Clients --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($clients as $client)
            {{-- Lien vers la page détail du client --}}
            <a href="{{ route('client.show', $client->id) }}" class="block group">
                <div class="bg-white border-2 border-gray-100 rounded-3xl p-6 hover:border-indigo-600 hover:shadow-xl transition-all h-full flex flex-col justify-between cursor-pointer">
                    
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-black uppercase text-gray-500">
                                {{ $client->trucks_count }} Camion(s)
                            </span>
                        </div>

                        <h4 class="text-xl font-black text-gray-900 mb-1">{{ $client->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $client->contact_person ?? 'Aucun contact' }}</p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-50 flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-400">{{ $client->phone ?? '-' }}</span>
                        <span class="text-indigo-600 text-xs font-black uppercase tracking-widest group-hover:underline">Gérer le parc &rarr;</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400 font-bold">
                Vous n'avez pas encore de clients. Commencez par en ajouter un !
            </div>
        @endforelse
    </div>
</div>