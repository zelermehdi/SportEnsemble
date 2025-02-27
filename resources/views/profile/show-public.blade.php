@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-green-700">Profil de {{ $user->name }}</h2>

        <!-- Avatar -->
        @if($user->avatar)
            <div class="mb-4">
                <img 
                    src="{{ asset('storage/' . $user->avatar) }}" 
                    alt="Avatar de {{ $user->name }}" 
                    class="h-24 w-24 object-cover rounded-full"
                >
            </div>
        @endif

        <!-- Bio -->
        @if($user->bio)
            <p class="text-gray-700 mb-2">
                <strong>Bio :</strong> {{ $user->bio }}
            </p>
        @endif

        <!-- Ville -->
        @if($user->ville)
            <p class="text-gray-700 mb-2">
                <strong>Ville :</strong> {{ $user->ville }}
            </p>
        @endif

        <!-- Sports favoris -->
        @if($user->sports_favoris)
            <p class="text-gray-700 mb-2">
                <strong>Sports favoris :</strong> {{ $user->sports_favoris }}
            </p>
        @endif

        <!-- Email (si vous souhaitez le montrer, ce n’est pas obligatoire) -->
        <p class="text-gray-700">
            <strong>Email :</strong> {{ $user->email }}
        </p>
    </div>
@endsection
