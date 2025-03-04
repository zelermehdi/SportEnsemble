@extends('layouts.app')

@section('content')
<h2>Galerie : {{ $evenement->titre }}</h2>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @forelse($photos as $photo)
        <div>
            <img src="{{ asset('storage/'.$photo->path) }}" alt="Photo de l'événement" class="rounded shadow">
        </div>
    @empty
        <p>Aucune photo pour le moment.</p>
    @endforelse
</div>

<a href="{{ route('photos.create', $evenement) }}" class="block mt-4 bg-green-500 text-white px-4 py-2 rounded">
    Ajouter une photo
</a>
@endsection
