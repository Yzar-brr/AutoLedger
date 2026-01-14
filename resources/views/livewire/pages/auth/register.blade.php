<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    
    // Ajout d'une propriété pour basculer l'affichage après l'envoi
    public bool $waitingForApproval = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // 1. Création de l'utilisateur (is_approved sera à 0 par défaut en base)
        $user = User::create($validated);

        event(new Registered($user));

        // 2. SURTOUT PAS DE Auth::login($user); 
        // On ne connecte pas l'utilisateur tant qu'on n'a pas validé son compte.

        // 3. On affiche le message de confirmation
        $this->waitingForApproval = true;
    }
}; ?>

<div>
    @if ($waitingForApproval)
        {{-- Message affiché APRES avoir cliqué sur Register --}}
        <div class="p-6 bg-indigo-50 border border-indigo-100 rounded-2xl text-center">
            <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-lg font-black text-indigo-900 uppercase tracking-tighter mb-2">Demande envoyée</h3>
            <p class="text-sm text-indigo-700 leading-relaxed mb-6">
                Votre inscription a bien été prise en compte. <br>
                <strong>Un administrateur doit valider votre accès</strong> avant que vous puissiez vous connecter.
            </p>
            <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white font-bold rounded-xl text-xs uppercase tracking-widest hover:bg-indigo-700 transition">
                Retour à l'accueil
            </a>
        </div>
    @else
        {{-- Le formulaire standard --}}
        <form wire:submit="register">
            <div>
                <x-input-label for="name" :value="__('Nom complet')" />
                <x-text-input wire:model="name" id="name" class="block mt-1 w-full" type="text" name="name" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email professionnel')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Mot de passe')" />
                <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirmation du mot de passe')" />
                <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}" wire:navigate>
                    {{ __('Déjà inscrit ?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Demander l\'accès') }}
                </x-primary-button>
            </div>
        </form>
    @endif
</div>