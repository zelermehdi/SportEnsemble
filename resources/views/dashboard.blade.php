@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6 text-green-700">Mon tableau de bord</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Mes événements organisés -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-3 text-green-700">Mes événements organisés</h3>
        @if($evenementsOrganises->isEmpty())
            <p class="text-gray-600">Vous n'organisez aucun événement pour le moment.</p>
        @else
            <ul class="list-disc pl-5 space-y-1">
                @foreach($evenementsOrganises as $evenement)
                    <li class="text-gray-800">
                        {{ $evenement->titre }} – 
                        <a href="{{ route('evenements.show', $evenement) }}" class="text-blue-500 hover:underline">
                            Voir
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Événements auxquels je participe -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold mb-3 text-green-700">Événements auxquels je participe</h3>
        @if($evenementsParticipes->isEmpty())
            <p class="text-gray-600">Vous ne participez à aucun événement pour le moment.</p>
        @else
            <ul class="list-disc pl-5 space-y-1">
                @foreach($evenementsParticipes as $evenement)
                    <li class="text-gray-800">
                        {{ $evenement->titre }} – 
                        <a href="{{ route('evenements.show', $evenement) }}" class="text-blue-500 hover:underline">
                            Voir
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<!-- **Carte au milieu du tableau de bord** -->
<div class="bg-white shadow-md rounded-lg p-6 my-6">
    <h3 class="text-xl font-semibold mb-3 text-green-700 text-center">Carte des événements</h3>
    <div id="mapid" class="w-full h-96"></div>
</div>

<!-- Mes invitations -->
<div class="bg-white shadow-md rounded-lg p-6 mt-6">
    <h3 class="text-xl font-semibold mb-3 text-green-700">Mes Invitations</h3>
    @php
        $invitations = auth()->user()->invitationsRecues ?? collect([]);
    @endphp

    @if($invitations->isEmpty())
        <p class="text-gray-600">Vous n’avez reçu aucune invitation.</p>
    @else
        <ul class="space-y-2">
            @foreach($invitations as $invitation)
                <li class="flex flex-col md:flex-row md:justify-between md:items-center bg-green-50 p-3 rounded-lg">
                    <div class="text-gray-800 mb-2 md:mb-0">
                        Invitation pour 
                        <strong>{{ $invitation->evenement->titre }}</strong>
                        de 
                        <strong>{{ $invitation->inviteur->name }}</strong>
                    </div>
                    <div class="flex space-x-2">
                        <!-- Accepter -->
                        <form action="{{ route('invitations.accepter', $invitation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                Accepter
                            </button>
                        </form>

                        <!-- Refuser -->
                        <form action="{{ route('invitations.refuser', $invitation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                Refuser
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
@push('scripts')
<script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialiser la carte centrée sur un point par défaut
    var map = L.map('mapid').setView([36.7525, 3.04197], 7);

    // 2. Ajouter le "tile layer" OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // 3. Charger TOUS les événements depuis Laravel (via Blade et JSON)
    var evenements = @json($tousLesEvenements);

    // 4. Ajouter des marqueurs pour chaque événement
    var markers = [];
    evenements.forEach(function(event) {
        if (event.latitude && event.longitude) {
            var marker = L.marker([event.latitude, event.longitude]).addTo(map)
                .bindPopup("<strong>" + event.titre + "</strong><br>" + event.lieu);
            markers.push(marker);
        }
    });

    // 5. Ajuster le zoom pour voir tous les marqueurs
    if (markers.length > 0) {
        var group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds());
    }
});
</script>
@endpush

@endpush
