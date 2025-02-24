<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SportEnsemble') }}</title>

    <!-- Polices Google (exemple Montserrat) -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link 
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" 
        rel="stylesheet"
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F7F9FA; /* fond léger */
        }
    </style>
</head>
<body class="text-gray-900 flex flex-col min-h-screen">
    <!-- Navbar (fixe) -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <!-- Logo + titre -->
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <img src="/images/logo-sport.png" alt="Logo" class="h-8 w-8">
                <span class="text-2xl font-bold text-green-600">SportEnsemble</span>
            </a>
            
            <!-- Menu principal -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('evenements.index') }}" class="text-gray-700 hover:text-green-600 transition">Événements</a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-green-600 transition">Admin</a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-green-600 transition">Mon Tableau de Bord</a>

                    <!-- Déconnexion -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-green-600 transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 transition">Connexion</a>
                    <a href="{{ route('register') }}" 
                       class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition">
                       Inscription
                    </a>
                @endauth
            </div>

            <!-- Menu mobile (burger) -->
            <div class="md:hidden" x-data="{ open: false }">
                <button @click="open = !open" class="focus:outline-none">
                    <svg class="w-6 h-6 text-gray-700 hover:text-green-600" fill="none" 
                         stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" 
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Menu déroulant mobile -->
                <div 
                    class="absolute top-14 right-0 w-48 bg-white border border-gray-200 rounded-lg shadow-md p-4 flex flex-col space-y-2"
                    x-show="open" x-transition @click.away="open = false"
                >
                    <a href="{{ route('evenements.index') }}" class="text-gray-700 hover:text-green-600 transition">Événements</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-green-600 transition">Admin</a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-green-600 transition">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-green-600 transition">
                                Déconnexion
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 transition">Connexion</a>
                        <a href="{{ route('register') }}" 
                           class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition">
                           Inscription
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <!-- On ajoute pt-20 pour laisser la place à la navbar fixe.
         Et on ajoute flex-1 pour que ce bloc s'étire et "pousse" le footer en bas. -->
    <main class="container mx-auto px-4 pt-20 flex-1">
        @include('partials.flash-messages') <!-- messages de succès/erreur -->
        
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-6 shadow-inner">
        <div class="container mx-auto px-4 text-center md:text-left md:flex md:justify-between">
            <div class="mb-4 md:mb-0">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-green-600">SportEnsemble</a>
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} SportEnsemble. Tous droits réservés.
                </p>
            </div>
            <div class="flex space-x-4 justify-center md:justify-end text-gray-500">
                <a href="#" class="hover:text-green-600">À propos</a>
                <a href="#" class="hover:text-green-600">Contact</a>
                <a href="#" class="hover:text-green-600">Confidentialité</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
