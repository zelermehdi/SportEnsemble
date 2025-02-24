@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded-md shadow-md">
    <h2 class="text-2xl font-bold mb-4 text-green-700">Mon profil</h2>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT') <!-- si vous voulez utiliser PUT -->

        <div>
            <label for="name" class="block mb-1 font-semibold">Nom</label>
            <input 
                type="text" 
                name="name" 
                id="name"
                class="w-full border border-gray-300 rounded px-2 py-1"
                value="{{ old('name', $user->name) }}"
                required
            >
            @error('name')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Avatar -->
        <div>
            <label for="avatar" class="block mb-1 font-semibold">Photo de profil</label>
            @if($user->avatar)
                <div class="mb-2">
                    <img 
                        src="{{ asset('storage/'.$user->avatar) }}" 
                        alt="Avatar" 
                        class="h-16 w-16 object-cover rounded-full"
                    >
                </div>
            @endif
            <input
                type="file"
                name="avatar"
                id="avatar"
                accept="image/*"
            >
            @error('avatar')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bio -->
        <div>
            <label for="bio" class="block mb-1 font-semibold">Biographie</label>
            <textarea
                name="bio"
                id="bio"
                rows="3"
                class="w-full border border-gray-300 rounded px-2 py-1"
            >{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ville -->
        <div>
            <label for="ville" class="block mb-1 font-semibold">Ville</label>
            <input
                type="text"
                name="ville"
                id="ville"
                class="w-full border border-gray-300 rounded px-2 py-1"
                value="{{ old('ville', $user->ville) }}"
            >
            @error('ville')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sports favoris -->
        <div>
            <label for="sports_favoris" class="block mb-1 font-semibold">Sports favoris</label>
            <input
                type="text"
                name="sports_favoris"
                id="sports_favoris"
                class="w-full border border-gray-300 rounded px-2 py-1"
                value="{{ old('sports_favoris', $user->sports_favoris) }}"
                placeholder="Ex : football, basket, running..."
            >
            @error('sports_favoris')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <button 
                type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
            >
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
