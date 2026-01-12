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

    <div class="py-12" x-data="{ activeTab: 'frais', showNotes: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-3xl p-8">
                
                <div class="flex flex-col md:flex-row gap-10">
                    
                    <div class="md:w-1/3 lg:w-1/4"> 
                        <div class="sticky top-6">
                            {{-- Image ou Fond Gris --}}
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

                        <div x-show="activeTab === 'frais'" x-transition:enter="transition ease-out duration-300">
                            
                            <div class="mb-10 bg-slate-50 p-6 rounded-3xl border border-slate-200">
                                <h5 class="text-slate-800 font-bold mb-4 flex items-center uppercase text-xs tracking-widest">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Enregistrer une dépense
                                </h5>
                                <form wire:submit.prevent="addExpense" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Libellé</label>
                                        <input type="text" wire:model="label" placeholder="ex: Révision complète" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                                        @error('label') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Montant (€)</label>
                                        <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                                        @error('amount') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Détails / Commentaires</label>
                                        <textarea wire:model="notes" rows="2" placeholder="Informations complémentaires..." class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm"></textarea>
                                    </div>
                                    <div class="md:col-span-2 flex justify-end">
                                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-8 rounded-xl text-xs uppercase tracking-widest shadow-lg shadow-indigo-100 transition">
                                            Ajouter au suivi
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="flex justify-between items-center mb-6">
                                <h4 class="font-black text-gray-900 uppercase text-lg tracking-tighter">Liste des interventions</h4>
                                
                                <button @click="showNotes = !showNotes" 
                                        type="button"
                                        class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-200 bg-white text-[10px] font-black text-indigo-600 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                                    <svg class="w-4 h-4 transition-transform" :class="showNotes ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    <span x-text="showNotes ? 'Masquer les notes' : 'Afficher les notes'"></span>
                                </button>
                            </div>

                            <div class="space-y-4">
                                @forelse($vehicle->expenses->sortByDesc('created_at') as $expense)
                                    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                        <div class="px-6 py-5 flex justify-between items-center">
                                            <div>
                                                <p class="font-bold text-gray-900">{{ $expense->label }}</p>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $expense->created_at->format('d/m/Y') }}</p>
                                            </div>
                                            <div class="text-lg font-black text-gray-900 bg-gray-50 px-4 py-1.5 rounded-lg border border-gray-100">
                                                {{ number_format($expense->amount, 2, ',', ' ') }} €
                                            </div>
                                        </div>
                                        
                                        <div x-show="showNotes" x-collapse>
                                            <div class="px-6 pb-5">
                                                <div class="p-4 bg-slate-50 rounded-xl border-l-4 border-indigo-400 text-sm text-gray-600 italic">
                                                    @if($expense->notes)
                                                        {{ $expense->notes }}
                                                    @else
                                                        <span class="text-gray-400">Aucun commentaire enregistré pour cette opération.</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16 border-2 border-dashed border-gray-100 rounded-3xl">
                                        <p class="text-gray-300 font-bold uppercase tracking-widest text-sm">Aucun frais enregistré</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div x-show="activeTab === 'galerie'" x-transition:enter="transition ease-out duration-300" style="display: none;">
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                {{-- Image principale actuelle --}}
                                @if($vehicle->image_path)
                                    <div class="group relative aspect-square rounded-3xl overflow-hidden border-2 border-indigo-50 shadow-sm">
                                        <img src="{{ Storage::url($vehicle->image_path) }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-indigo-900/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                            <span class="text-white text-[10px] font-black uppercase tracking-widest">Image Principale</span>
                                        </div>
                                    </div>
                                @endif
                                
                                {{-- Placeholder pour futures images --}}
                                <button class="aspect-square rounded-3xl border-2 border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400 hover:border-indigo-300 hover:text-indigo-500 transition-all group">
                                    <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center mb-2 group-hover:bg-indigo-50 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Ajouter photo</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>