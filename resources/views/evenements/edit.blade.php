@extends('layouts.app')

@section('content')
    <form action="{{ route('evenements.update', $evenement) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')
        
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <label class="block text-lg font-medium text-gray-700 mb-2">Modifier le statut :</label>
        <div class="relative">
            @if($evenement->statut === 'complet')
                <!-- Si l'événement est complet, on affiche un message et on désactive le select -->
                <p class="text-sm text-red-500 mb-2">L'événement est complet et ne peut plus être modifié.</p>
                <select name="statut" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 shadow-sm" disabled>
                    <option value="complet" selected>🔴 Complet</option>
                </select>
            @else
                <select name="statut" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 shadow-sm">
                    <option value="ouvert" @selected(old('statut', $evenement->statut) === 'ouvert')>🟢 Ouvert</option>
                    <option value="fermé" @selected(old('statut', $evenement->statut) === 'fermé')>⚪ Fermé</option>
                    <option value="complet" @selected(old('statut', $evenement->statut) === 'complet')>🔴 Complet</option>
                </select>
            @endif
        </div>
        
        @if($evenement->statut !== 'complet')
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-600 transition w-full mt-4">
                🔄 Mettre à jour
            </button>
        @endif
    </form>

    <!-- Bouton d'annulation avec confirmation (affiché seulement si l'événement n'est pas complet) -->
    @if($evenement->statut !== 'complet')
        <form action="{{ route('evenements.annuler', $evenement) }}" method="POST" class="mt-4 bg-red-50 shadow-md rounded-lg p-6"
              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cet événement ?');">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg shadow-md hover:bg-red-600 transition w-full">
                ❌ Annuler l'événement
            </button>
        </form>
    @endif
@endsection
