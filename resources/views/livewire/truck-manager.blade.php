<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($trucks as $truck)
        <div class="bg-white border-2 border-gray-100 rounded-3xl p-6 hover:border-indigo-600 hover:shadow-xl transition-all flex flex-col justify-between group relative">
            
            {{-- En-tête de la carte avec le badge --}}
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h4 class="text-xl font-black text-gray-900 mb-1">{{ $truck->plate_number }}</h4>
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ $truck->brand }}</p>
                </div>
                {{-- Le badge discret comme sur la page client --}}
                <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] font-black uppercase text-gray-500">
                    {{ $truck->interventions_count ?? $truck->interventions()->count() }} Interventions
                </span>
            </div>

            {{-- Bas de la carte avec les actions --}}
            <div class="mt-6 pt-6 border-t border-gray-50 flex justify-between items-center" x-data="{ openDelete: false }">
                
                {{-- Supprimer --}}
                <button @click="openDelete = true" class="text-gray-300 hover:text-red-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>

                {{-- Lien détails --}}
                <a href="{{ route('truck.show', $truck->id) }}" class="text-indigo-600 text-xs font-black uppercase tracking-widest group-hover:underline">
                    Détails &rarr;
                </a>

                {{-- La modale compacte Oui/Non --}}
                <template x-teleport="body">
                    <div x-show="openDelete" 
                         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                         style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
                        <div @click.away="openDelete = false" 
                             class="bg-white rounded-2xl p-5 shadow-2xl border border-gray-100"
                             style="max-width: 240px; width: 100%;">
                            <div class="text-center mb-5">
                                <h3 class="text-sm font-bold text-gray-900">Supprimer le camion ?</h3>
                            </div>
                            <div class="flex gap-2">
                                <button @click="openDelete = false" class="flex-1 py-2 bg-gray-100 text-[10px] font-bold uppercase text-gray-600 rounded-lg hover:bg-gray-200 transition">Non</button>
                                <button wire:click="deleteTruck({{ $truck->id }})" @click="openDelete = false" class="flex-1 py-2 bg-red-600 text-[10px] font-bold uppercase text-white rounded-lg hover:bg-red-700 transition">Oui</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    @empty
        <div class="col-span-2 text-center py-20 border-2 border-dashed border-gray-200 rounded-3xl text-gray-400 font-bold uppercase text-[10px]">
            Aucun camion enregistré.
        </div>
    @endforelse
</div>