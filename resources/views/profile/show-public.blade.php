@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 rounded-xl shadow-lg">
    <div class="text-center">
        <!-- Avatar -->
        @if($user->avatar)
            <div class="mx-auto w-32 h-32 mb-4 relative">
                <img 
                    src="{{ asset('storage/' . $user->avatar) }}" 
                    alt="Avatar de {{ $user->name }}" 
                    class="h-32 w-32 object-cover rounded-full border-4 border-green-500 shadow-md transition-transform transform hover:scale-105"
                >
            </div>
        @endif

        <!-- Nom -->
        <h2 class="text-3xl font-bold text-green-700 mb-2">{{ $user->name }}</h2>
        <p class="text-gray-500 text-lg mb-4">📩 {{ $user->email }}</p>

        <div class="bg-gray-100 p-4 rounded-lg shadow-inner">
            <!-- Bio -->
            @if($user->bio)
                <p class="text-gray-700 mb-2">
                    <strong>📝 Bio :</strong> {{ $user->bio }}
                </p>
            @endif

            <!-- Ville -->
            @if($user->ville)
                <p class="text-gray-700 mb-2">
                    <strong>📍 Ville :</strong> {{ $user->ville }}
                </p>
            @endif

            <!-- Sports favoris -->
            @if($user->sports_favoris)
                <p class="text-gray-700 mb-2">
                    <strong>⚽🏀🏃 Sports favoris :</strong> {{ $user->sports_favoris }}
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
