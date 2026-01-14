<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <livewire:welcome.navigation />
            @endif

            <div class="max-w-7xl mx-auto p-6 lg:p-8">
<div class="flex justify-center">
    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" class="h-20 w-20">
        <circle cx="50" cy="50" r="45" fill="#6D28D9" />
        
        <g stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path d="M25 60h50c2 0 4-2 4-4v-5c0-3-2-6-5-7l-10-4c-2-1-4-1-6 0h-15l-8 11h-10c-2 0-3 1-3 3v4c0 1 1 2 3 2z" />
            
            <circle cx="38" cy="62" r="5" fill="#6D28D9" stroke="white" stroke-width="2.5" />
            
            <circle cx="65" cy="62" r="5" fill="#6D28D9" stroke="white" stroke-width="2.5" />
            
            <path d="M43 40l7-5h10l5 5" stroke-width="2" />
            
            <path d="M15 45h6M12 52h5" stroke-opacity="0.6" />
        </g>
    </svg>
</div>
                <div class="mt-16">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                            <div>
                                <div class="h-16 w-16 bg-red-50 dark:bg-red-800/20 flex items-center justify-center rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="w-7 h-7 stroke-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>

                                <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Notes aux utilisateurs</h2>

                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                                    L'accès à ce site web est restreint, afin d'obtenir des identifiants, veuillez remplir le formulaire d'inscription.
                                </p>
                            </div>
                    </div>
                </div>


                {{-- Footer --}}
                <div class="flex justify-center mt-16 px-0 sm:items-center sm:justify-between">
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-start sm:ms-0">
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                    <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-end sm:ms-0">
                        <div>2025 © Nextway - Micro-entreprise <z class="text-red-400">4511Z<z></div>
                        <div>Représenté par Sofiane WIBAUT | LICENSE MIT</div>
                        <div class="text-gray-400"> Contact : sofianewibaut@gmail.com</div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
