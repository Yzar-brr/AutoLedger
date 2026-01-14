<div x-data="{ showForm: @entangle('showForm') }">
    
    <div class="flex justify-center">
        <div class="mb-6 flex-row justify-center items-center p-4">
            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Liste des véhicules</h3>
            <button @click="showForm = !showForm" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition">
                <span x-text="showForm ? 'Annuler' : '+ Ajouter un Camion'"></span>
            </button>
        </div>
    </div>


    {{-- Formulaire d'ajout --}}
    <div x-show="showForm" x-collapse class="mb-8 bg-slate-50 p-6 rounded-3xl border border-slate-200 p-4 mr-4 ml-4">
        <form wire:submit.prevent="addTruck">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Marque / Modèle</label>
                    <input type="text" wire:model="brand" placeholder="Ex: Renault Kerax" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500">
                    @error('brand') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 tracking-widest">Immatriculation</label>
                    <input type="text" wire:model="plate_number" placeholder="Ex: AA-123-BB" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500">
                    @error('plate_number') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- BOUTON DE VALIDATION --}}
            <div class="mt-4 flex justify-end">
    <button type="submit" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200/50 transition active:scale-95">
        Confirmer l'ajout
    </button>
</div>
        </form>
    </div>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($trucks as $truck)
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex justify-between items-center group hover:border-indigo-200 transition">
            <div>
                <h4 class="text-lg font-black text-gray-900">{{ $truck->plate_number }}</h4>
                <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ $truck->brand }}</p>
            </div>
            
            <div class="flex items-center space-x-2" x-data="{ openDelete: false }">
                <button @click="openDelete = true" class="p-2 text-gray-300 hover:text-red-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>

                <a href="{{ route('truck.show', $truck->id) }}" class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition">
                    Détails &rarr;
                </a>

                <template x-teleport="body">
                    <div x-show="openDelete" 
                         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                         style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
                        
                        <div @click.away="openDelete = false" 
                             x-show="openDelete"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="scale-95 opacity-0"
                             x-transition:enter-end="scale-100 opacity-100"
                             class="bg-white rounded-2xl p-5 shadow-2xl border border-gray-100"
                             style="max-width: 240px; width: 100%;">
                            
                            <div class="text-center mb-5">
                                <h3 class="text-sm font-bold text-gray-900">Supprimer le camion ?</h3>
                                <p class="text-xs text-gray-500 mt-1 text-center">Toutes les interventions liées seront effacées.</p>
                            </div>

                            <div class="flex gap-2">
                                <button @click="openDelete = false" 
                                        class="flex-1 py-2 bg-gray-100 text-[10px] font-bold uppercase tracking-widest text-gray-600 rounded-lg hover:bg-gray-200 transition">
                                    Non
                                </button>
                                <button wire:click="deleteTruck({{ $truck->id }})" 
                                        @click="openDelete = false"
                                        class="flex-1 py-2 bg-red-600 text-[10px] font-bold uppercase tracking-widest text-white rounded-lg hover:bg-red-700 transition">
                                    Oui
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    @empty
        <div class="col-span-2 text-center py-10 border-2 border-dashed border-gray-100 rounded-3xl text-gray-300 font-bold uppercase text-[10px]">
            Aucun camion enregistré.
        </div>
    @endforelse
</div>
</div>