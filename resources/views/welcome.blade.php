@extends('layouts.app')

@section('content')
<div class="bg-green-50 rounded-xl p-6 flex flex-col md:flex-row items-center md:space-x-8">
    <!-- Image de sport -->
    <div class="md:w-1/2">
        <img src="/images/DALL·E 2025-02-26 15.15.36 - A professional and sleek logo for a sports event app called 'SportEnsemble'. The logo should have a modern and minimalist design, with strong and eleg.webp" alt="Hero Sport" class="rounded-lg shadow-lg">
    </div>

    <!-- Texte de présentation -->
    <div class="md:w-1/2 mt-6 md:mt-0">
        <h1 class="text-3xl md:text-4xl font-bold text-green-700 mb-4">
            Rejoignez nos événements sportifs près de chez vous!
        </h1>
        <p class="text-gray-700 mb-4">
            Découvrez et organisez des rencontres sportives, élargissez votre réseau 
            et pratiquez vos sports favoris. Avec SportEnsemble, c’est plus facile de 
            trouver des coéquipiers ou de lancer un match improvisé!
        </p>
        <div class="space-x-2">
            <a 
                href="{{ route('evenements.index') }}" 
                class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition"
            >
                Voir les événements
            </a>
            <a 
                href="{{ route('register') }}"
                class="inline-block bg-white border border-green-600 text-green-600 px-4 py-2 rounded-lg 
                       hover:bg-green-600 hover:text-white transition"
            >
                Rejoindre maintenant
            </a>
        </div>
    </div>
</div>

<!-- Section d’info supplémentaire -->
<div class="mt-12 bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Comment ça marche ?</h2>
    <ol class="list-decimal list-inside text-gray-700 space-y-2">
        <li>Créez un compte ou connectez-vous</li>
        <li>Parcourez les événements sportifs près de chez vous</li>
        <li>Rejoignez une partie ou organisez la vôtre</li>
        <li>Invitez vos amis et rencontrez de nouveaux partenaires</li>
        <li>Profitez d’une communauté sportive active!</li>
    </ol>
</div>
@endsection
