<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Parc de : {{ $client->name }}
            </h2>

            {{-- Bouton Retour Harmonisé --}}
            <a href="{{ route('notes') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-indigo-100 rounded-xl shadow-sm text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:bg-indigo-50 transition">
                <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour aux clients
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Composant pour gérer les camions du client --}}
            @livewire('truck-manager', ['client' => $client])
        </div>
    </div>
</x-app-layout>