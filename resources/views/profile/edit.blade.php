@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-3xl font-bold mb-6 text-green-700 text-center">👤 Mon profil</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg border border-green-300 shadow-md">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Nom -->
        <div>
            <label for="name" class="block mb-1 font-semibold text-gray-700">📛 Nom</label>
            <input 
                type="text" 
                name="name" 
                id="name"
                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 transition"
                value="{{ old('name', $user->name) }}"
                required
            >
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Avatar -->
        <div>
            <label for="avatar" class="block mb-2 font-semibold text-gray-700">📷 Photo de profil</label>
            @if($user->avatar)
                <div class="relative mb-3 w-24 h-24">
                    <img 
                        src="{{ asset('storage/'.$user->avatar) }}" 
                        alt="Avatar" 
                        class="h-24 w-24 object-cover rounded-full border-4 border-green-500 shadow-md transition-transform transform hover:scale-110"
                    >
                </div>
            @endif
            <input
                type="file"
                name="avatar"
                id="avatar"
                accept="image/*"
                class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 transition"
            >
            @error('avatar')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bio -->
        <div>
            <label for="bio" class="block mb-1 font-semibold text-gray-700">📝 Biographie</label>
            <textarea
                name="bio"
                id="bio"
                rows="4"
                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 transition"
                placeholder="Parlez un peu de vous..."
            >{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ville -->
        <div>
            <label for="ville" class="block mb-1 font-semibold text-gray-700">🏙️ Ville</label>
            <input
                type="text"
                name="ville"
                id="ville"
                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 transition"
                value="{{ old('ville', $user->ville) }}"
                placeholder="Votre ville..."
            >
            @error('ville')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sports favoris -->
        <div>
            <label for="sports_favoris" class="block mb-1 font-semibold text-gray-700">⚽🏀🏃 Sports favoris</label>
            <input
                type="text"
                name="sports_favoris"
                id="sports_favoris"
                class="w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-green-300 transition"
                value="{{ old('sports_favoris', $user->sports_favoris) }}"
                placeholder="Ex : football, basket, running..."
            >
            @error('sports_favoris')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bouton de soumission -->
        <div class="mt-6 text-center">
            <button 
                type="submit"
                class="w-full bg-green-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-700 transition font-semibold text-lg"
            >
                ✅ Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
