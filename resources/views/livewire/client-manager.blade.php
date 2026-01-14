<div x-data="{ showForm: @entangle('showForm') }">

    {{-- En-tête + Bouton Ajouter --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        {{-- Titre et Sous-titre --}}
        <div class="flex-1">
            <h3 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Mes Clients</h3>
            <p class="text-sm text-gray-500">Sélectionnez un client pour gérer sa flotte.</p>
        </div>

        {{-- Bouton --}}
        <button @click="showForm = !showForm"
            class="inline-flex items-center justify-center px-6 py-4 sm:py-3 bg-indigo-600 hover:bg-indigo-700 text-white border border-transparent rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200/50 transition-all active:scale-95 w-full sm:w-auto min-w-fit">

            <svg x-show="!showForm" class="w-3 h-3 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
            </svg>

            <svg x-show="showForm" class="w-3 h-3 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
            </svg>

            <span class="whitespace-nowrap" x-text="showForm ? 'Fermer' : 'Nouveau Client'"></span>
        </button>
    </div>

    {{-- Formulaire d'ajout (Dépliable) --}}
    <div x-show="showForm" x-collapse class="mb-10 bg-indigo-50 p-8 rounded-3xl border border-indigo-100">
        <form wire:submit.prevent="addClient" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nom Société /
                    Client</label>
                <input type="text" wire:model="name"
                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 font-bold">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Contact
                    (Nom)</label>
                <input type="text" wire:model="contact_person"
                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500">
            </div>
            <div>
                <label
                    class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Téléphone</label>
                <input type="text" wire:model="phone"
                    class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500">
            </div>
            <div class="md:col-span-3 flex justify-end">
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl text-xs uppercase tracking-widest shadow-md transition">
                    Enregistrer le client
                </button>
            </div>
        </form>
    </div>
    {{-- Liste des Clients --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($clients as $client)
    <div x-data="{ confirmingDelete: false }" class="relative group h-full">

        {{-- Lien vers la page détail --}}
        <a href="{{ route('client.show', $client->id) }}" class="block h-full">
            <div class="bg-white border-2 border-gray-100 rounded-3xl p-6 hover:border-indigo-600 hover:shadow-xl transition-all h-full flex flex-col justify-between cursor-pointer">
                
                {{-- Haut de carte : Icone et Compteur --}}
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-black uppercase text-gray-500">
                            {{ $client->trucks_count }} Camion(s)
                        </span>
                    </div>

                    <h4 class="text-xl font-black text-gray-900 mb-1">{{ $client->name }}</h4>
                    <p class="text-sm text-gray-500 mb-4">{{ $client->contact_person ?? 'Aucun contact' }}</p>
                </div>

                {{-- NOUVEL EMPLACEMENT : Bouton Supprimer entre contact et footer --}}
                <div class="py-2">
                    <button @click.prevent.stop="confirmingDelete = true" type="button"
                            class="flex items-center text-gray-300 hover:text-red-600 transition-colors group/btn">
                        <div class="p-2 bg-gray-50 group-hover/btn:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-[10px] font-black uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity">Supprimer le client</span>
                    </button>
                </div>

                {{-- Footer de carte --}}
                <div class="mt-4 pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-xs font-bold text-gray-400">{{ $client->phone ?? '-' }}</span>
                    <span class="text-indigo-600 text-xs font-black uppercase tracking-widest group-hover:underline">Gérer &rarr;</span>
                </div>
            </div>
        </a>

        {{-- 3. Modale de confirmation (Teleportée au body) --}}
        <template x-teleport="body">
            <div x-show="confirmingDelete" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" style="display: none;">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md" @click="confirmingDelete = false"></div>
                <div class="relative bg-white rounded-[2.5rem] p-8 shadow-2xl max-w-xs w-full text-center border border-gray-100" @click.stop>
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-2">Supprimer ?</h3>
                    <p class="text-[10px] text-gray-400 mb-6 uppercase font-bold">{{ $client->name }}</p>
                    <div class="flex gap-3">
                        <button @click="confirmingDelete = false" class="flex-1 py-3 bg-gray-100 text-[10px] font-black uppercase text-gray-500 rounded-2xl hover:bg-gray-200 transition">Non</button>
                        <button wire:click="deleteClient({{ $client->id }})" @click="confirmingDelete = false" class="flex-1 py-3 bg-red-600 text-[10px] font-black uppercase text-white rounded-2xl hover:bg-red-700 transition">Oui</button>
                    </div>
                </div>
            </div>
        </template>
    </div>
    @empty
    <div class="col-span-full text-center py-20 border-2 border-dashed border-gray-200 rounded-[3rem] text-gray-400 font-black uppercase text-[10px] tracking-widest">
        Aucun client trouvé.
    </div>
    @endforelse
</div>
</div>