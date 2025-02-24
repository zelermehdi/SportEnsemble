@extends('layouts.app')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <h2 class="text-3xl font-bold text-green-700 mb-4">{{ $evenement->titre }}</h2>
    
    @if($evenement->description)
        <p class="text-gray-700 mb-3">{{ $evenement->description }}</p>
    @else
        <p class="text-gray-500 italic mb-3">Aucune description fournie.</p>
    @endif

    <p class="text-gray-600 mb-1">
        <strong>Date :</strong> 
        {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
    </p>
    <p class="text-gray-600 mb-1">
        <strong>Lieu :</strong> {{ $evenement->lieu }}
    </p>
    <p class="text-gray-600 mb-3">
        <strong>Max participants :</strong>
        {{ $evenement->max_participants ?? 'Illimité' }}
    </p>

    @auth
        @if($evenement->participations->contains('user_id', auth()->id()))
            <form action="{{ route('participations.seRetirer', $evenement) }}" method="POST" class="mb-4">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition"
                >
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
                    {{ $participation->user->name }}
                </li>
            @endforeach
        </ul>
    @endif
</div>

<!-- Invitation -->
@auth
<div class="bg-white shadow-md rounded-lg p-6">
    <h3 class="text-xl font-semibold mb-3 text-green-700">Inviter un utilisateur</h3>
    <form action="{{ route('invitations.inviter', $evenement) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="invite_id" class="block text-gray-700 font-medium mb-2">
                Sélectionner un utilisateur :
            </label>
            <select
                name="invite_id"
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
            >
                @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button
            type="submit"
            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition"
        >
            Envoyer l'invitation
        </button>
    </form>
</div>
@endauth
@endsection
