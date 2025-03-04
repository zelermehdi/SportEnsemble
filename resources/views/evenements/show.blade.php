@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-3xl font-bold text-green-700 mb-4">{{ $evenement->titre }}</h2>

    @if($evenement->latitude && $evenement->longitude)
        <div id="mapid" style="height: 300px;"></div>
    @endif

    <p class="text-gray-600 mb-1">
        <strong>Date :</strong> 
        {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
    </p>
    <p class="text-gray-600 mb-1">
        <strong>Lieu :</strong> {{ $evenement->lieu }}
    </p>
    <p class="text-gray-600 mb-3">
        <strong>Max participants :</strong> {{ $evenement->max_participants ?? 'Illimité' }}
    </p>

    @auth
        @if($evenement->participations->contains('user_id', auth()->id()))
            <form action="{{ route('participations.seRetirer', $evenement) }}" method="POST" class="mb-4">
                @csrf
                @method('DELETE')
                <button class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    Quitter l'événement
                </button>
            </form>
        @endif
    @endauth
</div>

<!-- Chat Section (Livewire) -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h4 class="text-xl font-semibold mb-3 text-green-700">Chat</h4>
    <livewire:chat-evenement :evenementId="$evenement->id" />
</div>

<!-- Participants -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h3 class="text-xl font-semibold mb-3 text-green-700">Participants</h3>
    @if($evenement->participations->isEmpty())
        <p class="text-gray-500">Aucun participant pour le moment.</p>
    @else
        <ul class="list-disc pl-5 space-y-1">
            @foreach($evenement->participations as $participation)
                <li class="text-gray-800">
                    <a href="{{ route('users.show', $participation->user) }}" class="text-blue-500 underline">
                        {{ $participation->user->name }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>

<!-- 🌟 Galerie de photos avec Likes et Commentaires -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h3 class="text-xl font-semibold text-green-700 mb-3">Photos de l'événement</h3>

    @if($evenement->photos->isEmpty())
        <p class="text-gray-500">Aucune photo pour le moment. Soyez le premier à en ajouter !</p>
    @else
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($evenement->photos as $photo)
                <div class="relative">
                    <img src="{{ asset('storage/'.$photo->path) }}" alt="Photo" class="rounded-lg shadow w-full">

                    <!-- Bouton de suppression -->
                    @if(auth()->id() == $photo->user_id || auth()->id() == $evenement->user_id)
                        <form action="{{ route('photos.destroy', $photo) }}" method="POST" class="absolute top-1 right-1">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white text-xs px-2 py-1 rounded">✖</button>
                        </form>
                    @endif

                    <!-- Likes et Commentaires -->
                    <div class="flex justify-between items-center mt-2">
                        <form action="{{ route('photos.like', $photo) }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1">
                                <span class="text-gray-700">{{ $photo->likes->count() }}</span>
                                @if($photo->isLikedByUser())
                                    ❤️
                                @else
                                    🤍
                                @endif
                            </button>
                        </form>

                        <span class="text-sm text-gray-500">{{ $photo->comments->count() }} Commentaires</span>
                    </div>

                    <!-- Formulaire de commentaire -->
                    <form action="{{ route('photos.comment', $photo) }}" method="POST" class="mt-2">
                        @csrf
                        <input type="text" name="contenu" placeholder="Ajouter un commentaire..." 
                            class="border p-2 rounded w-full">
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

<!-- 📸 Ajouter une photo -->
@if(auth()->check())
    <div class="bg-white shadow-md rounded-lg p-6">
        <h3 class="text-xl font-semibold text-green-700 mb-3">Ajouter une photo</h3>
        <form action="{{ route('photos.store', $evenement) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="file" name="photo" required class="border p-2 rounded">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                Télécharger
            </button>
        </form>
    </div>
@endif

<!-- Invitation -->
@auth
<div class="bg-white shadow-md rounded-lg p-6">
    <h3 class="text-xl font-semibold mb-3 text-green-700">Inviter un utilisateur</h3>
    <form action="{{ route('invitations.inviter', $evenement) }}" method="POST" class="space-y-4">
        @csrf
        <select name="invite_id" class="w-full p-2 border border-gray-300 rounded-lg">
            @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
            Envoyer l'invitation
        </button>
    </form>
</div>
@endauth
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($evenement->latitude && $evenement->longitude)
        var mymap = L.map('mapid').setView([{{ $evenement->latitude }}, {{ $evenement->longitude }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(mymap);

        L.marker([{{ $evenement->latitude }}, {{ $evenement->longitude }}]).addTo(mymap)
          .bindPopup("{{ $evenement->titre }}");
    @endif
});
</script>
@endpush
