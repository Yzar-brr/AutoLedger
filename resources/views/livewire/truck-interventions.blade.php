<div x-data="{ activeTab: 'rapports', showForm: false, showImage: null,deleteId: null }">

    <div class="flex items-center space-x-2 mb-8 bg-gray-100/50 p-1.5 rounded-2xl w-fit m-4">
        <button @click="activeTab = 'rapports'"
            :class="activeTab === 'rapports' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-200">
            Rapports d'intervention
        </button>

        <button @click="activeTab = 'entretien'"
            :class="activeTab === 'entretien' ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
            class="px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all duration-200">
            Plan d'Entretien
        </button>
    </div>

    <div x-show="activeTab === 'rapports'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4">

        <div class="mb-6 flex justify-between items-center px-4">
            <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">Historique des interventions</h3>
            <button @click="showForm = !showForm"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition-all active:scale-95">
                <span x-text="showForm ? 'Annuler' : '+ Nouveau rapport d\'intervention'"></span>
            </button>
        </div>

        <div x-show="showForm" x-collapse
            class="mb-10 bg-slate-50 p-6 rounded-3xl border border-slate-200 shadow-inner mx-4">
            <form wire:submit.prevent="addIntervention" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Date</label>
                    <input type="date" wire:model="date"
                        class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Technicien</label>
                    <input type="text" wire:model="technician" placeholder="Nom du garage ou mécanicien"
                        class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Prix
                        (€)</label>
                    <input type="number" step="0.01" wire:model="price"
                        class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                </div>

                {{-- Nouveau champ Kilométrage --}}
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Kilométrage
                        (km)</label>
                    <input type="number" wire:model="mileage" placeholder="Ex: 155000"
                        class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Temps passé
                        (min)</label>
                    <input type="number" wire:model="duration"
                        class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm">
                </div>

                <div class="md:col-span-2">
                    <label
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Détails</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full border-gray-200 rounded-xl shadow-sm focus:ring-indigo-500 text-sm"
                        placeholder="Travaux effectués..."></textarea>
                </div>

                <div class="md:col-span-2">
                    <label
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Photos</label>
                    <input type="file" wire:model="newPhotos" multiple
                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700">
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" @click="showForm = false"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-md transition-all active:scale-95">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-6 px-4">
            @forelse($interventions as $intervention)
            <div class="bg-white border-2 border-gray-100 rounded-3xl shadow-sm overflow-hidden">
                <div class="p-6 flex justify-between items-start bg-slate-50/30 border-b border-gray-50">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <span
                                class="px-3 py-1 bg-white border border-gray-200 text-gray-600 rounded-lg text-[10px] font-black uppercase">
                                {{ \Carbon\Carbon::parse($intervention->date)->format('d/m/Y') }}
                            </span>
                            <span
                                class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg text-[10px] font-black uppercase">
                                {{ $intervention->technician }}
                            </span>
                        </div>
                        <p class="mt-4 text-sm text-gray-700 font-medium">{{ $intervention->description }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-black text-gray-900">{{ number_format($intervention->price, 2, ',', '') }} €</div>
                        <div class="text-[10px] text-indigo-400 font-black uppercase">{{ $intervention->duration }} min
                        </div>
                    </div>
                </div>

                @if($intervention->photos)
                <div class="px-6 py-4 flex flex-wrap gap-3">
                    @foreach($intervention->photos as $photo)
                    <img src="{{ Storage::url($photo) }}" @click="showImage = '{{ Storage::url($photo) }}'"
                        class="w-16 h-16 rounded-xl object-cover cursor-zoom-in border-2 border-white shadow-sm hover:-translate-y-1 transition-all">
                    @endforeach
                </div>
                @endif

                {{-- Footer avec Kilométrage à gauche et Suppression à droite --}}
                <div class="px-6 py-4 flex justify-between items-center border-t border-gray-50/50">
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span
                            class="text-[10px] font-black uppercase tracking-widest {{ $intervention->mileage ? 'text-slate-600' : 'text-slate-300 italic' }}">
                            {{ $intervention->mileage ? number_format($intervention->mileage, 0, ',', ' ') . ' km' :
                            'Kilométrage inconnu' }}
                        </span>
                    </div>

                    <button @click="deleteId = {{ $intervention->id }}"
                        class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            @empty
            <div
                class="text-center py-20 border-2 border-dashed border-gray-100 rounded-[40px] text-gray-300 font-black uppercase text-[10px]">
                Carnet vide
            </div>
            @endforelse
        </div>
    </div>

    {{-- Plan d'entretien --}}
    {{-- Plan d'entretien --}}
    {{-- Plan d'entretien --}}

    <div x-show="activeTab === 'entretien'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" style="display: none;">
        <div class="bg-white border-2 border-gray-100 rounded-[2.5rem] p-4 md:p-8 mx-4 shadow-sm">

            {{-- Header --}}
            <div
                class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-gray-50 pb-6">
                <div>
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest">Plan d'Entretien & Contrôles
                    </h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">
                        Vérification périodique : {{ $truck->plate_number }}
                    </p>
                </div>
                <button wire:click="saveMaintenancePlan"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-md transition-all active:scale-95">
                    Enregistrer le plan
                </button>
            </div>

            {{-- Message de succès --}}
            @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-2xl flex items-center justify-between">
                <span>{{ session('message') }}</span>
                <button @click="show = false">×</button>
            </div>
            @endif

            @php
            $sections = [
            'PNEUS' => ['pneus_av' => 'Pneus AV', 'pneus_ar' => 'Pneus AR'],
            'FREINAGE' => [
            'plaquettes_av' => 'Plaquettes AV', 'plaquettes_ar' => 'Plaquettes AR',
            'disques_av' => 'Disques AV', 'disques_ar' => 'Disques AR', 'frein_a_main' => 'Frein à main'
            ],
            'NIVEAUX' => [
            'niveau_lr' => 'Refroidissement (LR)', 'niveau_huile' => "Huile Moteur",
            'niveau_liquide_frein' => 'Liquide de frein'
            ],
            'AUTRES' => [
            'essuie_glace_av' => 'Essuie-glace AV', 'eclairage_av' => 'Éclairage AV', 'eclairage_ar' => 'Éclairage AR'
            ]
            ];
            @endphp

            {{-- Formulaire de saisie --}}
            <div class="space-y-12">
                @foreach($sections as $sectionName => $items)
                <section>
                    <div class="flex items-center gap-4 mb-6">
                        <h4 class="text-[11px] font-black text-indigo-600 uppercase tracking-[0.2em] whitespace-nowrap">
                            {{ $sectionName }}
                        </h4>
                        <div class="h-[1px] w-full bg-gray-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($items as $key => $label)
                        <div
                            class="bg-slate-50/50 rounded-2xl p-4 border border-gray-100 hover:border-indigo-100 transition-colors">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">
                                {{ $label }}
                            </label>
                            <div class="flex items-center p-1 bg-gray-200/50 rounded-xl w-full">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" wire:model="maintenance.{{ $key }}" name="{{ $key }}"
                                        value="bon" class="hidden peer">
                                    <div
                                        class="text-center py-2 rounded-lg text-[9px] font-black uppercase transition-all peer-checked:bg-emerald-500 peer-checked:text-white text-gray-400 hover:text-gray-600">
                                        Bon
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" wire:model="maintenance.{{ $key }}" name="{{ $key }}"
                                        value="a_prevoir" class="hidden peer">
                                    <div
                                        class="text-center py-2 rounded-lg text-[9px] font-black uppercase transition-all peer-checked:bg-amber-500 peer-checked:text-white text-gray-400 hover:text-gray-600">
                                        À prévoir
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" wire:model="maintenance.{{ $key }}" name="{{ $key }}"
                                        value="urgent" class="hidden peer">
                                    <div
                                        class="text-center py-2 rounded-lg text-[9px] font-black uppercase transition-all peer-checked:bg-red-500 peer-checked:text-white text-gray-400 hover:text-gray-600">
                                        Urgent
                                    </div>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </section>
                @endforeach
            </div>

            {{-- Bloc de date de mise à jour --}}
            <div class="mt-12 p-6 border-2 border-dashed border-gray-100 rounded-3xl text-center">
                <p class="text-[10px] font-black text-gray-300 uppercase tracking-[0.2em]">
                    Dernière mise à jour :
                    @if($lastMaintenancePlan)
                    {{ $lastMaintenancePlan->check_date->format('d/m/Y') }} à {{
                    $lastMaintenancePlan->created_at->format('H:i') }}
                    @else
                    Aucun contrôle enregistré
                    @endif
                </p>
            </div>

            {{-- LISTE DE TOUS LES ENTRETIENS (HISTORIQUE) --}}
            <div class="mt-12 space-y-4">
                <div class="flex items-center gap-4 px-4 mb-6">
                    <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] whitespace-nowrap">
                        Historique des rapports</h4>
                    <div class="h-[1px] w-full bg-gray-50"></div>
                </div>

                @forelse($maintenancePlans as $plan)
                <div
                    class="bg-white border-2 border-gray-50 rounded-[2rem] p-5 shadow-sm hover:border-indigo-100 transition-all">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center border border-gray-100 text-indigo-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-[11px] font-black text-gray-900 uppercase">Rapport du {{
                                    $plan->check_date->format('d/m/Y') }}</div>
                                <div class="text-[9px] font-bold text-gray-400 uppercase mt-0.5">Par {{
                                    $plan->user->name ?? 'Mécanicien' }}</div>
                            </div>
                        </div>

                        {{-- Résumé dynamique --}}
                        <div class="flex flex-wrap gap-2">
                            @php
                            $data = collect($plan->data);
                            $urgents = $data->filter(fn($v) => $v === 'urgent')->count();
                            $aprevoir = $data->filter(fn($v) => $v === 'a_prevoir')->count();
                            @endphp

                            @if($urgents > 0)
                            <span
                                class="px-3 py-1 bg-red-50 text-red-600 rounded-xl text-[8px] font-black uppercase border border-red-100">
                                {{ $urgents }} Urgent
                            </span>
                            @endif

                            @if($aprevoir > 0)
                            <span
                                class="px-3 py-1 bg-amber-50 text-amber-600 rounded-xl text-[8px] font-black uppercase border border-amber-100">
                                {{ $aprevoir }} À prévoir
                            </span>
                            @endif

                            @if($urgents == 0 && $aprevoir == 0)
                            <span
                                class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-xl text-[8px] font-black uppercase border border-emerald-100">
                                Conforme
                            </span>
                            @endif
                        </div>
                        <a href="{{ route('maintenance.pdf', $plan->id) }}"
                            class="p-2 text-gray-300 hover:text-indigo-600 transition-colors"
                            title="Télécharger le PDF">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 border-2 border-dashed border-gray-50 rounded-[2rem]">
                    <p class="text-[10px] font-black text-gray-300 uppercase">Aucun historique disponible</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modales --}}
    {{-- Modales --}}
    {{-- Modales --}}

    <template x-teleport="body">
        <div x-show="deleteId !== null"
            class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
            style="display: none;">
            <div @click.away="deleteId = null"
                class="bg-white rounded-3xl p-6 shadow-2xl w-full max-w-[260px] text-center">
                <h3 class="text-[11px] font-black text-gray-900 uppercase mb-6">Supprimer l'intervention ?</h3>
                <div class="flex gap-2">
                    <button @click="deleteId = null"
                        class="flex-1 py-2.5 bg-gray-50 text-[10px] font-black uppercase text-gray-400 rounded-xl">Non</button>
                    <button @click="$wire.deleteIntervention(deleteId); deleteId = null"
                        class="flex-1 py-2.5 bg-red-600 text-[10px] font-black uppercase text-white rounded-xl">Oui</button>
                </div>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="showImage !== null"
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xl"
            style="display: none;" @click="showImage = null">
            <div class="relative max-w-lg w-full flex flex-col items-center">
                <img :src="showImage"
                    class="rounded-[2.5rem] border-[6px] border-white shadow-2xl object-contain max-h-[70vh]">
                <button @click="showImage = null" class="mt-6 p-3 bg-white rounded-full shadow-xl">
                    <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </template>

</div>