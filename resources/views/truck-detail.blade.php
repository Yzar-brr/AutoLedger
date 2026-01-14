l<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Interventions : {{ $truck->plate_number }}
        </h2>
        {{-- Retour vers le détail du client parent --}}
        <a href="{{ route('client.show', $truck->client_id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-indigo-200 rounded-xl font-bold text-[10px] text-indigo-600 uppercase tracking-widest shadow-sm hover:bg-indigo-50 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour au Parc
        </a>
    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Le composant final avec prix, temps, technicien et photos --}}
            @livewire('truck-interventions', ['truck' => $truck])
        </div>
    </div>
</x-app-layout>