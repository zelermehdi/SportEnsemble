@extends('layouts.app')

@section('content')
<h2>Ajouter une photo à l’événement: {{ $evenement->titre }}</h2>
<form action="{{ route('photos.store', $evenement) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="photo">Choisir une photo :</label>
        <input type="file" name="photo" id="photo" accept="image/*" required>
        @error('photo')
            <div class="text-red-500">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="bg-green-500 text-white px-4 py-2">Uploader</button>
</form>
@endsection
