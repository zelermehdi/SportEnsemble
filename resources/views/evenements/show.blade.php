@extends('layouts.app')

@section('content')

<!-- Carte et Informations de l'Événement -->
<div class="bg-white shadow-lg rounded-xl p-8 mb-8 transition-transform transform hover:scale-[1.01]">
    <h2 class="text-4xl font-bold text-green-700 mb-6 text-center">
        📍 {{ $evenement->titre }}
    </h2>

    @if($evenement->latitude && $evenement->longitude)
        <div id="mapid" class="rounded-lg overflow-hidden shadow-md" style="height: 350px;"></div>
    @endif

    <div class="grid md:grid-cols-2 gap-6 mt-6">
        <div class="space-y-3 text-gray-700">
            <p class="flex items-center text-lg">
                <span class="text-2xl text-green-500">📅</span> 
                <strong class="ml-2">Date :</strong> {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
            </p>
            <p class="flex items-center text-lg">
                <span class="text-2xl text-green-500">📍</span> 
                <strong class="ml-2">Lieu :</strong> {{ $evenement->lieu }}
            </p>
            <p class="flex items-center text-lg">
                <span class="text-2xl text-green-500">👥</span> 
                <strong class="ml-2">Participants max :</strong> {{ $evenement->max_participants ?? 'Illimité' }}
            </p>
        </div>

        @auth
            @if($evenement->participations->contains('user_id', auth()->id()))
                <form action="{{ route('participations.seRetirer', $evenement) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="w-full bg-red-500 text-white px-5 py-3 rounded-lg hover:bg-red-600 transition font-semibold">
                        ❌ Quitter l'événement
                    </button>
                </form>
            @endif
        @endauth
    </div>
</div>

<!-- Chat Section -->
<div class="bg-white shadow-lg rounded-xl p-6 mb-8">
    <h4 class="text-2xl font-semibold mb-4 text-green-700">💬 Chat en direct</h4>
    <livewire:chat-evenement :evenementId="$evenement->id" />
</div>

<!-- Participants -->
<div class="bg-white shadow-lg rounded-xl p-6 mb-8">
    <h3 class="text-2xl font-semibold mb-4 text-green-700">👥 Participants</h3>
    @if($evenement->participations->isEmpty())
        <p class="text-gray-500 italic">Aucun participant pour le moment.</p>
    @else
        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($evenement->participations as $participation)
                <li class="flex items-center bg-gray-100 p-3 rounded-lg shadow-md">
                    <span class="text-green-500 text-xl">✅</span>
                    <a href="{{ route('users.show', $participation->user) }}" class="ml-3 text-gray-800 font-semibold hover:text-blue-500">
                        {{ $participation->user->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<!-- Galerie de photos avec Likes et Commentaires -->
<div class="bg-white shadow-lg rounded-xl p-6 mb-8">
    <h3 class="text-2xl font-semibold text-green-700 mb-4">📸 Photos de l'événement</h3>
    @if($evenement->photos->isEmpty())
        <p class="text-gray-500">Aucune photo pour le moment. Soyez le premier à en ajouter !</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($evenement->photos as $photo)
                <div class="relative group">
                    <img src="{{ asset('storage/'.$photo->path) }}" alt="Photo" class="rounded-lg shadow-lg transition-transform transform group-hover:scale-105">
                    
                    <!-- Likes et Commentaires -->
                    <div class="flex justify-between items-center mt-2">
                        <form action="{{ route('photos.like', $photo) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1">
                                <span class="text-gray-700">{{ $photo->likes->count() }}</span>
                                @if($photo->isLikedByUser()) ❤️ @else 🤍 @endif
                            </button>
                        </form>
                        <span class="text-sm text-gray-500">{{ $photo->comments->count() }} Commentaires</span>
                    </div>

                    <!-- Formulaire de commentaire -->
                    <form action="{{ route('photos.comment', $photo) }}" method="POST" class="mt-2">
                        @csrf
                        <input type="text" name="contenu" placeholder="Ajouter un commentaire..." class="border p-2 rounded w-full">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Commenter</button>
                    </form>

                    <!-- Liste des commentaires -->
                    <ul class="mt-2">
                        @foreach($photo->comments as $comment)
                            <li><strong>{{ $comment->user->name }}</strong>: {{ $comment->contenu }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Ajouter une photo -->
@if(auth()->check())
    <div class="bg-white shadow-lg rounded-xl p-6 mb-8">
        <h3 class="text-2xl font-semibold text-green-700 mb-4">➕ Ajouter une photo</h3>
        <form action="{{ route('photos.store', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="file" name="photo" required class="w-full p-3 border border-gray-300 rounded-lg">
            <button type="submit" class="bg-green-500 text-white px-5 py-3 rounded-lg hover:bg-green-600 transition">
                📤 Télécharger
            </button>
        </form>
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($evenement->latitude && $evenement->longitude)
        var mymap = L.map('mapid').setView([{{ $evenement->latitude }}, {{ $evenement->longitude }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(mymap);

        L.marker([{{ $evenement->latitude }}, {{ $evenement->longitude }}])
          .addTo(mymap)
          .bindPopup("<strong>{{ $evenement->titre }}</strong>");
    @endif
});
</script>
@endpush
