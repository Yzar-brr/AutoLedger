<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion : {{ $vehicle->model }}
            </h2>
            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-bold flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                RETOUR AU PARC
            </a>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'frais', showImage: null, showForm: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-3xl p-8">
                
                <div class="flex flex-col md:flex-row gap-10">
                    
                    {{-- COLONNE GAUCHE : INFOS VEHICULE --}}
                    <div class="md:w-1/3 lg:w-1/4"> 
                        <div class="sticky top-6">
                            @if($vehicle->image_path)
                                <img src="{{ Storage::url($vehicle->image_path) }}" class="w-full rounded-2xl shadow-lg border-4 border-white object-cover aspect-square">
                            @else
                                <div class="w-full aspect-square bg-slate-200 rounded-2xl border-2 border-dashed border-slate-300 flex items-center justify-center p-6 text-center shadow-inner">
                                    <span class="text-slate-400 font-black uppercase tracking-tighter leading-none">PAS DE PHOTO</span>
                                </div>
                            @endif
                            
                            <div class="mt-6 text-center">
                                <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">{{ $vehicle->model }}</h1>
                                <div class="inline-block mt-2 px-4 py-1 bg-gray-800 text-white font-mono rounded-md shadow-sm text-sm tracking-widest uppercase">
                                    {{ $vehicle->registration }}
                                </div>

                                <div class="mt-8 space-y-3">
                                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-left">
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Prix d'achat</p>
                                        <p class="text-xl font-bold text-gray-800">{{ number_format($vehicle->purchase_price ?? 0, 2, ',', ' ') }} €</p>
                                    </div>

                                    <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 text-left">
                                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Prix de vente</p>
                                        <p class="text-2xl font-black text-indigo-900">{{ number_format($vehicle->selling_price ?? 0, 2, ',', ' ') }} €</p>
                                    </div>

                                    <div class="px-2 pt-2 space-y-2">
                                        <div class="flex justify-between text-xs font-bold uppercase">
                                            <span class="text-gray-400">Total Frais</span>
                                            <span class="text-red-500">- {{ number_format($vehicle->expenses->sum('amount'), 2, ',', ' ') }} €</span>
                                        </div>
                                        @php
                                            $marge = ($vehicle->selling_price ?? 0) - ($vehicle->purchase_price ?? 0) - $vehicle->expenses->sum('amount');
                                        @endphp
                                        <div class="flex justify-between items-center p-3 {{ $marge >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }} rounded-xl border border-current border-opacity-10">
                                            <span class="text-[10px] font-black uppercase">Marge Nette</span>
                                            <span class="text-lg font-black">{{ number_format($marge, 2, ',', ' ') }} €</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- COLONNE DROITE : TABS --}}
                    <div class="md:w-2/3 lg:w-3/4 border-l border-gray-100 md:pl-10">
                        
                        <div class="flex space-x-10 border-b border-gray-100 mb-8">
                            <button @click="activeTab = 'frais'" 
                                    :class="activeTab === 'frais' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                    class="pb-4 border-b-2 font-black uppercase text-xs tracking-widest transition-all">
                                Historique Frais
                            </button>
                            <button @click="activeTab = 'galerie'" 
                                    :class="activeTab === 'galerie' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-400 hover:text-gray-600'"
                                    class="pb-4 border-b-2 font-black uppercase text-xs tracking-widest transition-all">
                                Galerie Photos
                            </button>
                        </div>

                        {{-- CONTENU : FRAIS --}}
                        <div x-show="activeTab === 'frais'" x-transition:enter="transition ease-out duration-300">
                            
                            {{-- FORMULAIRE DÉPLIABLE --}}
                            <div class="mb-10 overflow-hidden border border-slate-200 rounded-3xl transition-all shadow-sm">
                                {{-- En-tête cliquable pour déplier --}}
                                <button @click="showForm = !showForm" 
                                        class="w-full bg-slate-50 p-6 flex justify-between items-center hover:bg-slate-100 transition focus:outline-none">
                                    <h5 class="text-slate-800 font-bold flex items-center uppercase text-xs tracking-widest">
                                        <svg class="w-5 h-5 mr-2 text-indigo-600 transition-transform duration-300" :class="showForm ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Enregistrer une dépense
                                    </h5>
                                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest" x-text="showForm ? 'Fermer' : 'Ouvrir'"></span>
                                </button>

                                {{-- Corps du formulaire (Replié par défaut) --}}
                                <div x-show="showForm" x-collapse class="p-6 bg-white border-t border-slate-100">
                                    <form wire:submit.prevent="addExpense" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Libellé</label>
                                            <input type="text" wire:model="label" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Montant (€)</label>
                                            <input type="number" step="0.01" wire:model="amount" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Notes</label>
                                            <textarea wire:model="notes" rows="2" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm"></textarea>
                                        </div>
                                        <div class="md:col-span-2 flex justify-end">
                                            <button type="submit" @click="showForm = false" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl text-xs uppercase tracking-widest shadow-lg transition">
                                                Enregistrer
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Liste Frais --}}
                            <div class="space-y-4">
                                @forelse($vehicle->expenses->sortByDesc('created_at') as $expense)
                                    <div x-data="{ open: false }" class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                                        <div class="px-6 py-5 flex justify-between items-center">
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $expense->label }}</p>
                                                <div class="flex items-center mt-1 space-x-3">
                                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $expense->created_at->format('d/m/Y') }}</span>
                                                    @if($expense->notes)
                                                        <button @click="open = !open" class="text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:underline focus:outline-none">
                                                            <span x-show="!open">Voir Notes</span>
                                                            <span x-show="open">Masquer</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-lg font-black text-gray-900 bg-gray-50 px-4 py-1.5 rounded-lg border border-gray-100">
                                                {{ number_format($expense->amount, 2, ',', ' ') }} €
                                            </div>
                                        </div>

                                        <div x-show="open" x-collapse class="px-6 pb-5 border-t border-gray-50 bg-indigo-50/30">
                                            <div class="pt-4 text-sm text-gray-600 leading-relaxed italic">
                                                {{ $expense->notes }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 border-2 border-dashed border-gray-100 rounded-3xl text-gray-300 font-bold uppercase text-sm">Aucun frais enregistré</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- CONTENU : GALERIE --}}
                        <div x-show="activeTab === 'galerie'" x-transition:enter="transition ease-out duration-300" style="display: none;">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                @foreach($vehicle->photos as $photo)
                                    <div class="group relative aspect-square rounded-3xl overflow-hidden border-2 border-indigo-50 shadow-sm">
                                        <img src="{{ Storage::url($photo->path) }}" 
                                             class="w-full h-full object-cover cursor-zoom-in"
                                             @click="showImage = '{{ Storage::url($photo->path) }}'">
                                        <button wire:click="deletePhoto({{ $photo->id }})" 
                                                wire:confirm="Supprimer cette photo ?"
                                                class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                @endforeach

                                <div class="relative aspect-square group">
                                    <input type="file" wire:model="newPhotos" multiple class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                    <div class="w-full h-full rounded-3xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 group-hover:border-indigo-300 group-hover:text-indigo-500 transition-all bg-white overflow-hidden relative">
                                        <div wire:loading wire:target="newPhotos" class="absolute inset-0 bg-white/90 z-30 flex flex-col items-center justify-center">
                                            <svg class="animate-spin h-8 w-8 text-indigo-600 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </div>
                                        <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-2 group-hover:bg-indigo-50 transition">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        </div>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Ajouter photo</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL ZOOM --}}
        <template x-teleport="body">
            <div x-show="showImage" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/95 p-4" style="display: none;">
                <button @click="showImage = null" class="absolute top-5 right-5 text-white/70 hover:text-white transition">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <img :src="showImage" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl border border-white/10" @click.away="showImage = null">
            </div>
        </template>
    </div>
</div>