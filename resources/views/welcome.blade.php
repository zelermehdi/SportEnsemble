@extends('layouts.app')

@section('content')
<div class="relative bg-cover bg-center h-screen flex items-center justify-center" style="background-image: url('/images/C:\laragon\www\SportEnsemble\public\images\DALL·E 2025-02-26 15.15.36 - A professional and sleek logo for a sports event app called 'SportEnsemble'. The logo should have a modern and minimalist design, with strong and eleg.webp');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative text-center text-white px-6">
        <h1 class="text-5xl font-extrabold mb-4 animate-fade-in">SportEnsemble</h1>
        <p class="text-lg mb-6 animate-fade-in">Rejoignez une communauté sportive et participez à des événements près de chez vous !</p>
        <div class="space-x-4 animate-fade-in">
            <a href="{{ route('evenements.index') }}" class="bg-green-500 px-6 py-3 rounded-lg text-white font-semibold shadow-lg hover:bg-green-600 transition">Voir les événements</a>
            <a href="{{ route('register') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold shadow-lg hover:bg-green-600 hover:text-white transition">Rejoindre maintenant</a>
        </div>
    </div>
</div>

<!-- Section Présentation -->
<div class="container mx-auto px-6 py-12">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Pourquoi SportEnsemble ?</h2>
    <div class="grid md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-xl transition">
            <img src="/icons/team.svg" class="w-16 mx-auto mb-4">
            <h3 class="text-xl font-semibold text-green-700">Rencontrez des sportifs</h3>
            <p class="text-gray-600">Trouvez facilement des partenaires et des équipes pour jouer avec vous.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-xl transition">
            <img src="/icons/schedule.svg" class="w-16 mx-auto mb-4">
            <h3 class="text-xl font-semibold text-green-700">Événements dynamiques</h3>
            <p class="text-gray-600">Rejoignez ou organisez des événements sportifs en quelques clics.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-xl transition">
            <img src="/icons/notification.svg" class="w-16 mx-auto mb-4">
            <h3 class="text-xl font-semibold text-green-700">Notifications instantanées</h3>
            <p class="text-gray-600">Recevez des alertes sur les événements et invitations en temps réel.</p>
        </div>
    </div>
</div>

<!-- Section Appel à l'action -->
<div class="bg-green-600 text-white text-center py-12 mt-12">
    <h2 class="text-3xl font-bold mb-4">Prêt à rejoindre l'aventure ?</h2>
    <p class="text-lg mb-6">Inscrivez-vous maintenant et participez à vos premiers événements !</p>
    <a href="{{ route('register') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-semibold shadow-lg hover:bg-green-700 hover:text-white transition">Créer un compte</a>
</div>
@endsection
