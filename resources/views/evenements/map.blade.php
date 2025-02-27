@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold text-green-700 mb-6">Carte des événements</h2>
    
    <!-- Conteneur pour la carte -->
    <div id="mapid" style="height: 500px;"></div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialiser la carte centré sur un point par défaut (ici Alger, zoom 8 pour une vue d'ensemble)
    var map = L.map('mapid').setView([36.7525, 3.04197], 8);

    // 2. Ajouter le tile layer d'OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // 3. Récupérer la liste des événements transmis par le contrôleur en JSON
    var evenements = @json($evenements);

    // 4. Parcourir chaque événement et ajouter un marqueur pour ceux qui ont des coordonnées
    evenements.forEach(function(event) {
        if (event.latitude && event.longitude) {
            L.marker([event.latitude, event.longitude]).addTo(map)
                .bindPopup("<strong>" + event.titre + "</strong><br>" + event.lieu);
        }
    });

    // 5. (Optionnel) Ajuster automatiquement le zoom pour englober tous les marqueurs
    if (evenements.length > 0) {
        var markers = evenements.filter(e => e.latitude && e.longitude)
            .map(e => L.marker([e.latitude, e.longitude]));
        if (markers.length > 0) {
            var group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds());
        }
    }
});
</script>
@endpush
