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
                <option value="foot">Football</option>
                <option value="course">Course</option>
                <option value="basket">Basketball</option>
                <option value="autre">Autre</option>
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
                    required
                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
                    value="{{ old('lieu') }}"
                    placeholder="Ex: Alger, Oran..."
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
            <label for="max_participants" class="block text-gray-700 font-medium mb-1">Nombre max. de participants</label>
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

        <div>
            <label for="niveau" class="block text-gray-700 font-medium mb-1">Niveau</label>
            <select
                name="niveau"
                required
                class="w-full p-2 border border-gray-300 rounded-lg"
            >
                <option value="débutant" @selected(old('niveau') === 'débutant')>Débutant</option>
                <option value="amateur" @selected(old('niveau') === 'amateur')>Amateur</option>
                <option value="pro" @selected(old('niveau') === 'pro')>Pro</option>
            </select>
            @error('niveau')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nouveau champ : tags -->
        <div>
            <label for="tags" class="block text-gray-700 font-medium mb-1">
                Tags (ex: "5v5, foot en salle")
            </label>
            <input
                type="text"
                name="tags"
                class="w-full p-2 border border-gray-300 rounded-lg"
                value="{{ old('tags') }}"
            >
            @error('tags')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
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