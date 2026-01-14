<div x-data="{ showForm: false, showImage: null }">

    {{-- En-tête + Bouton Déplier --}}
    <div class="mb-6 flex justify-between items-center">
        <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Carnet d'entretien : {{ $truck->plate_number }}</h3>
        <button @click="showForm = !showForm"
            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-xl text-[10px] uppercase tracking-widest shadow-lg transition-all flex items-center">
            <svg x-show="!showForm" class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span x-text="showForm ? 'Fermer' : 'Nouvelle Intervention'"></span>
        </button>
    </div>

    {{-- FORMULAIRE DÉPLIABLE (Inchangé mais prêt à l'emploi) --}}
    <div x-show="showForm" x-collapse class="mb-10 bg-slate-50 p-6 rounded-3xl border border-slate-200 shadow-inner">
        <form wire:submit.prevent="addIntervention" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Date</label>
                <input type="date" wire:model="date" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Technicien</label>
                <input type="text" wire:model="technician" placeholder="Ex: Jean Dupont" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Prix (€)</label>
                <input type="number" step="0.01" wire:model="price" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Temps passé (min)</label>
                <input type="number" wire:model="duration" placeholder="Ex: 120" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Détails de l'intervention</label>
                <textarea wire:model="description" rows="3" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm"></textarea>
            </div>
            <div class="md:col-span-2">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Photos</label>
                <input type="file" wire:model="newPhotos" multiple class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
            <div class="md:col-span-2 flex justify-end mt-2">
                <button type="submit" @click="showForm = false" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl text-xs uppercase tracking-widest shadow-md transition">
                    Enregistrer l'intervention
                </button>
            </div>
        </form>
    </div>

    {{-- LISTE DES INTERVENTIONS --}}
    <div class="space-y-6">
        @forelse($interventions as $intervention)
        <div class="bg-white border-2 border-gray-100 rounded-3xl shadow-sm overflow-hidden group hover:border-indigo-100 transition">

            {{-- En-tête de la carte --}}
            <div class="p-6 flex justify-between items-start bg-slate-50/30 border-b border-gray-50">
                <div class="flex-1">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">
                            {{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}
                        </span>
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase tracking-widest">
                            Technicien : {{ $intervention->technician }}
                        </span>
                    </div>
                    <p class="mt-4 text-sm text-gray-700 leading-relaxed font-medium">
                        {{ $intervention->description }}
                    </p>
                </div>

                <div class="text-right ml-4">
                    <div class="text-xl font-black text-gray-900 tracking-tighter">{{ number_format($intervention->price, 2, ',', ' ') }} €</div>
                    <div class="text-[10px] text-indigo-400 font-black uppercase tracking-widest mt-1">
                        {{ $intervention->duration }} minutes
                    </div>
                </div>
            </div>

            {{-- Galerie photos miniature avec padding --}}
            @if($intervention->photos)
            <div class="px-6 py-4 flex flex-wrap gap-3">
                @foreach($intervention->photos as $photo)
                <div class="relative group/img">
                    <img src="{{ Storage::url($photo) }}"
                        class="w-16 h-16 rounded-xl object-cover cursor-zoom-in border-2 border-white shadow-sm hover:shadow-indigo-200 transition-all hover:-translate-y-1"
                        @click="showImage = '{{ Storage::url($photo) }}'">
                </div>
                @endforeach
            </div>
            @endif

            {{-- Footer : Bouton supprimer --}}
            <div x-data="{ openDelete: false }" class="px-6 py-4 flex justify-end items-center border-t border-gray-50/50">
                
                {{-- Petite Corbeille Discrète --}}
                <button @click="openDelete = true" 
                        class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200"
                        title="Supprimer l'intervention">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>

                {{-- Pop-up de confirmation (gardée compacte et propre) --}}
                <template x-teleport="body">
                    <div x-show="openDelete" 
                         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
                         style="background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);">
                        
                        <div @click.away="openDelete = false" 
                             x-show="openDelete"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="scale-95 opacity-0" 
                             x-transition:enter-end="scale-100 opacity-100"
                             class="bg-white rounded-3xl p-6 shadow-2xl border border-gray-100 w-full max-w-[240px]">
                            
                            <div class="text-center mb-6">
                                <h3 class="text-[11px] font-black text-gray-900 uppercase tracking-widest">Supprimer ?</h3>
                            </div>
                            
                            <div class="flex gap-2">
                                <button @click="openDelete = false" 
                                        class="flex-1 py-2.5 bg-gray-50 text-[10px] font-black uppercase text-gray-400 rounded-xl hover:bg-gray-100 transition">
                                    Non
                                </button>
                                <button wire:click="deleteIntervention({{ $intervention->id }})" 
                                        @click="openDelete = false" 
                                        class="flex-1 py-2.5 bg-red-600 text-[10px] font-black uppercase text-white rounded-xl hover:bg-red-700 transition">
                                    Oui
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        @empty
        <div class="text-center py-20 border-2 border-dashed border-gray-100 rounded-[40px] text-gray-300 font-black uppercase text-[10px] tracking-[0.2em]">
            Carnet d'entretien vide
        </div>
        @endforelse
    </div>

    {{-- MODAL ZOOM --}}
<template x-teleport="body">
    <div x-show="showImage" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
         style="display: none;">
        
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-2xl" 
             @click="showImage = null"></div>

        <div class="relative flex flex-col items-center" 
             style="width: 100%; max-width: 450px;">
            
            {{-- L'Image stylisée --}}
            <div class="relative group" 
                 x-show="showImage"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="scale-90 opacity-0 translate-y-8"
                 x-transition:enter-end="scale-100 opacity-100 translate-y-0">
                
                <img :src="showImage" 
                     class="rounded-[2.5rem] border-[6px] border-white shadow-[0_25px_50px_-12px_rgba(0,0,0,0.5)] object-contain"
                     style="width: 100%; height: auto; max-height: 65vh;"
                     @click.stop>
            </div>

            <button @click="showImage = null" 
                    class="mt-8 group flex flex-col items-center space-y-2">
                <div class="p-3 bg-white text-gray-900 rounded-full shadow-xl group-hover:scale-110 group-active:scale-95 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span class="text-[9px] font-black text-white uppercase tracking-[0.3em] opacity-60 group-hover:opacity-100 transition-opacity">Fermer</span>
            </button>
        </div>
    </div>
</template>

</div>