<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SportEnsemble') }}</title>
    @csrfMeta
    <!-- Polices Google (exemple Montserrat) -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Leaflet CSS (pour la carte) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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

    <script>
        window.Laravel = {!! json_encode([
            'userId' => auth()->id(),
        ]) !!};
    </script>

</head>
<body class="text-gray-900 flex flex-col min-h-screen">
    <!-- Navbar -->
   <!-- Navbar -->
<nav x-data="{ open: false }" class="fixed w-full bg-white shadow-md z-50">
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
        <!-- Bouton menu mobile -->
        <div class="md:hidden">
            <button @click="open = !open" class="text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :class="{'hidden': open, 'inline-flex': !open}" d="M4 6h16M4 12h16M4 18h16"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :class="{'hidden': !open, 'inline-flex': open}" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <!-- Menu mobile -->
    <div x-show="open" x-cloak class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="{{ route('evenements.index') }}" class="block text-gray-700 hover:text-green-600 transition">
                Événements
            </a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="block text-gray-700 hover:text-green-600 transition">
                        Admin
                    </a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-green-600 transition">
                    Mon Profil
                </a>
                <a href="{{ route('dashboard') }}" class="block text-gray-700 hover:text-green-600 transition">
                    Tableau de Bord
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700 hover:text-green-600 transition">
                        Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block text-gray-700 hover:text-green-600 transition">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="block bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-bottom-right",
            timeOut: "3000",
        };

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>

    @stack('scripts')
</body>
</html>
