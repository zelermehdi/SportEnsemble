@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">{{ $evenement->titre }}</h2>
    <p class="text-gray-700 mb-4">{{ $evenement->description }}</p>
    
    <!-- Chat Section -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h4 class="text-xl font-semibold mb-3">Chat</h4>
        <div class="chat-container bg-gray-100 p-4 rounded-lg max-h-96 overflow-y-auto">
            @foreach($messages as $msg)
                <div class="message p-2 rounded-lg mb-2 @if($msg->user_id == auth()->id()) text-right bg-blue-100 @else bg-gray-200 @endif">
                    <strong>{{ $msg->user->name }}</strong> : {{ $msg->contenu }}
                </div>
            @endforeach
        </div>
        <form wire:submit.prevent="sendMessage" class="mt-3 flex space-x-2">
            <input type="text" wire:model="message" placeholder="Écrire un message..." class="flex-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            @error('message') <span class="text-red-500">{{ $message }}</span> @enderror
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">Envoyer</button>
        </form>
    </div>
    
    <!-- Notifications -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-3">Notifications</h3>
        @if(auth()->check())
            <ul class="list-disc pl-5 space-y-2">
                @forelse(auth()->user()->notifications as $notification)
                    <li class="flex justify-between items-center bg-gray-100 p-2 rounded-lg">
                        <span class="text-gray-800">{{ $notification->data['message'] }}</span>
                        <button wire:click="marquerCommeLue('{{ $notification->id }}')" class="text-red-500 hover:text-red-700">✖</button>
                    </li>
                @empty
                    <li class="text-gray-600">Aucune notification</li>
                @endforelse
            </ul>
        @else
            <p class="text-gray-600">Veuillez vous connecter pour voir vos notifications.</p>
        @endif
    </div>
    
    <!-- Participants -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-3">Participants</h3>
        <ul class="list-disc pl-5">
            @foreach($evenement->participations as $participation)
                <li class="text-gray-800">{{ $participation->user->name }}</li>
            @endforeach
        </ul>
    </div>
    
    @if(auth()->user() && $evenement->participations->contains('user_id', auth()->id()))
        <form action="{{ route('participations.retirer', $evenement->id) }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Quitter l'événement</button>
        </form>
    @endif
    
    @if(auth()->user())
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-xl font-semibold mb-3">Inviter un utilisateur</h3>
            <form action="{{ route('invitations.inviter', $evenement->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="invite_id" class="block text-gray-700 font-medium">Sélectionner un utilisateur :</label>
                    <select name="invite_id" class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
                        @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">Envoyer l'invitation</button>
            </form>
        </div>
    @endif
</div>
@endsection
