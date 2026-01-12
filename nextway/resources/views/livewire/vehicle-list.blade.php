<div>
    {{-- 1. Le Header (Titre de la page) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de bord - Parc Automobile') }}
        </h2>
    </x-slot>

    {{-- 2. Le fond gris global --}}
    <div class="py-12 bg-gray-100 min-h-screen">
        
        {{-- 3. Le Container centré avec marges --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Barre d'action : Bouton Ajouter --}}
            <div class="mb-6 flex justify-end">
                <button wire:click="openModal" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-200 transition duration-150 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    AJOUTER UN VÉHICULE
                </button>
            </div>

            {{-- Grille des véhicules --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($vehicles as $vehicle)
                <a href="{{ route('vehicles.show', $vehicle->id) }}" class="block group">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                        
                        {{-- ZONE IMAGE --}}
                        <div class="relative h-52 w-full bg-gray-200">
                            @if($vehicle->image_path)
                                <img src="{{ Storage::url($vehicle->image_path) }}" alt="Photo" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-300 flex items-center justify-center p-6 text-center">
                                    <span class="text-slate-500 font-black text-xl uppercase tracking-widest leading-tight">
                                        {{ $vehicle->model }}
                                    </span>
                                </div>
                            @endif
                            
                            {{-- Badge Statut --}}
                            @php
                                $color = match($vehicle->status) {
                                    'vendu' => 'bg-red-500',
                                    'atelier' => 'bg-amber-500',
                                    default => 'bg-emerald-500',
                                };
                            @endphp
                            <div class="absolute top-3 right-3 {{ $color }} text-white text-[10px] font-black px-2.5 py-1 rounded-lg shadow-md uppercase tracking-wider">
                                {{ $vehicle->status }}
                            </div>
                        </div>

                        {{-- Infos Véhicule --}}
                        <div class="p-6">
                            <div class="mb-4 text-left">
                                <p class="text-[10px] text-indigo-500 uppercase font-black tracking-[0.2em]">Désignation</p>
                                <h3 class="text-2xl font-bold text-gray-900 mt-1 truncate">{{ $vehicle->model }}</h3>
                            </div>
                            
                            <div class="flex justify-between items-center pt-4 border-t border-gray-50">
                                <span class="text-xs font-bold text-gray-400 uppercase">Plaque</span>
                                <div class="bg-white border-2 border-gray-200 rounded-lg px-3 py-1 shadow-sm">
                                    <span class="font-mono font-bold text-gray-700 text-sm tracking-widest uppercase">
                                        {{ $vehicle->registration }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modale d'ajout (Indépendante du container gris) --}}
    @if($isOpen)
    <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 overflow-hidden text-left">
                <div class="mb-6">
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Nouveau Véhicule</h3>
                    <p class="text-gray-500 text-sm">Remplissez les informations financières et techniques.</p>
                </div>
                
                <form wire:submit.prevent="save">
                    <div class="space-y-5">
                        {{-- Modèle --}}
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Modèle du véhicule</label>
                            <input type="text" wire:model="model" placeholder="ex: RENAULT CLIO 5" 
                                   class="block w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                            @error('model') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Immatriculation --}}
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Immatriculation</label>
                            <input type="text" wire:model="registration" placeholder="AB-123-CD"
                                   x-data @input="$el.value = $el.value.toUpperCase()"
                                   class="block w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono">
                            @error('registration') <span class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</span> @enderror
                        </div>

                        {{-- Grille Prix --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Prix d'achat (€)</label>
                                <input type="number" step="0.01" wire:model="purchase_price" class="block w-full border-gray-200 rounded-xl">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Prix de vente (€)</label>
                                <input type="number" step="0.01" wire:model="selling_price" class="block w-full border-gray-200 rounded-xl">
                            </div>
                        </div>

                        {{-- Statut --}}
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Statut parc</label>
                            <select wire:model="status" class="block w-full border-gray-200 rounded-xl font-bold uppercase text-sm">
                                <option value="disponible">Disponible</option>
                                <option value="vendu">Vendu</option>
                                <option value="atelier">Atelier</option>
                            </select>
                        </div>

                        {{-- Photo --}}
                        <div class="bg-indigo-50/50 p-4 rounded-2xl border-2 border-dashed border-indigo-100">
                            <label class="block text-xs font-black text-indigo-400 uppercase tracking-widest mb-2 text-center">Photo principale</label>
                            <div class="flex items-center justify-center">
                                @if ($image)
                                    <div class="relative">
                                        <img src="{{ $image->temporaryUrl() }}" class="h-24 w-32 object-cover rounded-xl shadow-md border-2 border-white">
                                        <button type="button" wire:click="$set('image', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </div>
                                @else
                                    <label class="cursor-pointer bg-white px-4 py-2 rounded-lg border border-indigo-200 text-indigo-600 text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition">
                                        <span>Sélectionner</span>
                                        <input type="file" wire:model="image" class="hidden" accept="image/*">
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" wire:click="closeModal" class="flex-1 bg-gray-100 text-gray-500 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition">ANNULER</button>
                        <button type="submit" class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition">ENREGISTRER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>