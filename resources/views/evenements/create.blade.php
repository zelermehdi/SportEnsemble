@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6 text-green-700">Créer un événement</h2>

<div class="bg-white shadow-md rounded-lg p-6 max-w-lg mx-auto">
    <form action="{{ route('evenements.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="titre" class="block text-gray-700 font-medium mb-1">Titre</label>
            <input
                type="text"
                name="titre"
                required
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
                value="{{ old('titre') }}"
                placeholder="Entrez un titre pour votre événement"
            >
            @error('titre')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="type_sport" class="block text-gray-700 font-medium mb-1">Type de sport</label>
            <select
                name="type_sport"
                required
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
            >
                <option value="foot" @selected(old('type_sport') === 'foot')>Football</option>
                <option value="course" @selected(old('type_sport') === 'course')>Course</option>
                <option value="basket" @selected(old('type_sport') === 'basket')>Basketball</option>
                <option value="autre" @selected(old('type_sport') === 'autre')>Autre</option>
            </select>
            @error('type_sport')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="lieu" class="block text-gray-700 font-medium mb-1">Lieu</label>
                <input
                    type="text"
                    name="lieu"
                    id="lieu"
                    required
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
                    value="{{ old('lieu') }}"
                    placeholder="Entrez un lieu ou sélectionnez sur la carte"
                >
                @error('lieu')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="date" class="block text-gray-700 font-medium mb-1">Date</label>
                <input
                    type="datetime-local"
                    name="date"
                    required
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
                >
                @error('date')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="max_participants" class="block text-gray-700 font-medium mb-1">
                Nombre max. de participants
            </label>
            <input
                type="number"
                name="max_participants"
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
                min="1"
                value="{{ old('max_participants') }}"
                placeholder="Laissez vide pour illimité"
            >
            @error('max_participants')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-gray-700 font-medium mb-1">Description</label>
            <textarea
                name="description"
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
                rows="4"
                placeholder="Décrivez votre événement (optionnel)"
            >{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Carte Leaflet -->
        <div class="my-4">
            <label class="block text-gray-700 font-medium mb-1">Localisation</label>
            <p class="text-sm text-gray-500 mb-2">
                Cliquez sur la carte ou saisissez un lieu pour définir l'emplacement exact.
            </p>
            <div id="mapid" style="height: 300px;"></div>

            <!-- Champs cachés pour latitude/longitude -->
            <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
            <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
        </div>

        <button
            type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition w-full"
        >
            Créer
        </button>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('mapid').setView([36.7525, 3.04197], 10);

    // Ajouter le tile d'OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    // Ajouter un marqueur draggable
    var marker = L.marker([36.7525, 3.04197], { draggable: true }).addTo(map);

    function updateLatLng(lat, lng, reverseGeocode = true) {
        document.getElementById('latitude').value = lat.toFixed(7);
        document.getElementById('longitude').value = lng.toFixed(7);

        // Reverse Geocoding pour trouver le nom du lieu
        if (reverseGeocode) {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('lieu').value = data.display_name;
                    }
                })
                .catch(error => console.error('Erreur Reverse Geocoding:', error));
        }
    }

    // Déplacement du marqueur => mise à jour coordonnées & lieu
    marker.on('dragend', function(e) {
        var latlng = marker.getLatLng();
        updateLatLng(latlng.lat, latlng.lng);
    });

    // Clic sur la carte => mise à jour du marqueur & coordonnées
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng.lat, e.latlng.lng);
    });

    // Quand l'utilisateur tape un lieu, chercher les coordonnées
    document.getElementById('lieu').addEventListener('change', function() {
        var address = this.value;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var place = data[0];
                    var lat = parseFloat(place.lat);
                    var lon = parseFloat(place.lon);

                    marker.setLatLng([lat, lon]);
                    map.setView([lat, lon], 14);
                    updateLatLng(lat, lon, false);
                }
            })
            .catch(error => console.error('Erreur Geocoding:', error));
    });
});
</script>
@endpush
