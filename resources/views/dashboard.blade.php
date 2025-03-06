@extends('layouts.app')

@section('content')
<h2 class="text-4xl font-bold mb-8 text-green-700 text-center">Mon Tableau de Bord</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Mes événements organisés -->
    <div class="bg-white shadow-lg rounded-xl p-6 transition hover:shadow-2xl hover:scale-105">
        <h3 class="text-xl font-semibold mb-4 text-green-700">🎯 Mes Événements Organisés</h3>
        @if($evenementsOrganises->isEmpty())
            <p class="text-gray-500">Vous n'organisez aucun événement pour le moment.</p>
        @else
            <ul class="list-disc pl-5 space-y-3 text-gray-700">
                @foreach($evenementsOrganises as $evenement)
                    <li>
                        <span class="font-semibold">{{ $evenement->titre }}</span> – 
                        <a href="{{ route('evenements.show', $evenement) }}" class="text-blue-600 hover:underline">Voir</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Événements auxquels je participe -->
    <div class="bg-white shadow-lg rounded-xl p-6 transition hover:shadow-2xl hover:scale-105">
        <h3 class="text-xl font-semibold mb-4 text-green-700">🏆 Événements où je participe</h3>
        @if($evenementsParticipes->isEmpty())
            <p class="text-gray-500">Vous ne participez à aucun événement pour le moment.</p>
        @else
            <ul class="list-disc pl-5 space-y-3 text-gray-700">
                @foreach($evenementsParticipes as $evenement)
                    <li>
                        <span class="font-semibold">{{ $evenement->titre }}</span> – 
                        <a href="{{ route('evenements.show', $evenement) }}" class="text-blue-600 hover:underline">Voir</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<!-- Carte des événements -->
<div class="bg-white shadow-lg rounded-xl p-6 my-8 transition hover:shadow-2xl">
    <h3 class="text-xl font-semibold mb-4 text-green-700 text-center">🗺️ Carte des événements</h3>
    <div id="mapid" class="w-full h-96 rounded-lg overflow-hidden"></div>
</div>

<!-- Invitations -->
<div class="bg-white shadow-lg rounded-xl p-6 mt-6 transition hover:shadow-2xl">
    <h3 class="text-xl font-semibold mb-4 text-green-700">📩 Mes Invitations</h3>
    @php
        $invitations = auth()->user()->invitationsRecues ?? collect([]);
    @endphp

    @if($invitations->isEmpty())
        <p class="text-gray-500">Vous n’avez reçu aucune invitation.</p>
    @else
        <ul class="space-y-4">
            @foreach($invitations as $invitation)
                <li class="flex flex-col md:flex-row md:justify-between md:items-center bg-green-50 p-4 rounded-lg shadow-sm hover:shadow-md transition">
                    <div class="text-gray-800">
                        🎟️ Invitation pour <strong>{{ $invitation->evenement->titre }}</strong> de <strong>{{ $invitation->inviteur->name }}</strong>
                    </div>
                    <div class="flex space-x-2 mt-3 md:mt-0">
                        <!-- Accepter -->
                        <form action="{{ route('invitations.accepter', $invitation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                ✅ Accepter
                            </button>
                        </form>

                        <!-- Refuser -->
                        <form action="{{ route('invitations.refuser', $invitation->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                ❌ Refuser
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
    <livewire:calendrier-evenements />

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('mapid').setView([36.7525, 3.04197], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    var evenements = @json($tousLesEvenements);
    var markers = [];
    evenements.forEach(function(event) {
        if (event.latitude && event.longitude) {
            var marker = L.marker([event.latitude, event.longitude]).addTo(map)
                .bindPopup(`<strong>${event.titre}</strong><br>${event.lieu}`);
            markers.push(marker);
        }
    });
    
    if (markers.length > 0) {
        var group = new L.featureGroup(markers);
        map.fitBounds(group.getBounds());
    }
});
</script>
@endpush
