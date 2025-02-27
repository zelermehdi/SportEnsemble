<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SportEnsemble') }}</title>

    <!-- Polices Google (exemple Montserrat) -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet CSS (pour la carte) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">

    <!-- Ton CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #F7F9FA; /* fond léger */
        }
    </style>
</head>
<body class="text-gray-900 flex flex-col min-h-screen">
    <!-- Navbar -->
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <img src="/images/DALL·E 2025-02-26 15.15.36 - A professional and sleek logo for a sports event app called 'SportEnsemble'. The logo should have a modern and minimalist design, with strong and eleg.webp" alt="Logo" class="h-8 w-8">
                <span class="text-2xl font-bold text-green-600">SportEnsemble</span>
            </a>
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('evenements.index') }}" class="text-gray-700 hover:text-green-600 transition">
                    Événements
                </a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.index') }}" class="text-gray-700 hover:text-green-600 transition">
                            Admin
                        </a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-green-600 transition">
                        Mon Profil
                    </a>
                    <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-green-600 transition">
                        Tableau de Bord
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-green-600 transition">
                            Déconnexion
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 transition">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition">
                        Inscription
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="container mx-auto px-4 pt-20 flex-1">
        @include('partials.flash-messages') 
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white py-6 shadow-inner">
        <div class="container mx-auto px-4 text-center md:text-left md:flex md:justify-between">
            <div class="mb-4 md:mb-0">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-green-600">
                    SportEnsemble
                </a>
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

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Pusher + Livewire Fix -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("✅ Pusher chargé...");

            if (typeof Livewire === "undefined") {
                console.error("❌ Livewire non chargé !");
            } else {
                console.log("✅ Livewire chargé !");
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
