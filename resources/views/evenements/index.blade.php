@extends('layouts.app')

@section('content')
    <h2 class="text-4xl font-bold mb-8 text-green-700 text-center">📅 Liste des événements sportifs</h2>

    <!-- Formulaire de filtrage -->
    <div class="bg-green-50 shadow-md rounded-xl p-6 mb-8">
        <form method="GET" action="{{ route('evenements.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <input
                type="text"
                name="q"
                placeholder="🔍 Rechercher un événement..."
                value="{{ request('q') }}"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 shadow-sm"
            >

            <select
                name="type_sport"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 shadow-sm"
            >
                <option value="all">Tous les sports</option>
                <option value="foot"   @selected(request('type_sport') === 'foot')>⚽ Football</option>
                <option value="course" @selected(request('type_sport') === 'course')>🏃 Course</option>
                <option value="basket" @selected(request('type_sport') === 'basket')>🏀 Basket</option>
                <option value="autre"  @selected(request('type_sport') === 'autre')>🔹 Autre</option>
            </select>

            <input
                type="text"
                name="lieu"
                placeholder="📍 Lieu"
                value="{{ request('lieu') }}"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 shadow-sm"
            >

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 shadow-sm"
            >

            <button
                type="submit"
                class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition w-full md:w-auto shadow-md"
            >
                🔍 Filtrer
            </button>
        </form>
    </div>

    <!-- Bouton de création d’événement -->
    <div class="mb-6 text-center">
        <a
            href="{{ route('evenements.create') }}"
            class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-600 transition inline-block text-lg"
        >
            ➕ Créer un événement
        </a>
    </div>

    <!-- Liste des événements -->
    @if($evenements->isEmpty())
        <p class="text-gray-600 text-center">Aucun événement trouvé.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($evenements as $evenement)
                <div class="bg-white shadow-md rounded-xl p-6 transition transform hover:scale-105 hover:shadow-xl">
                    <h3 class="text-xl font-semibold text-green-700 mb-3 flex items-center">
                        📍 {{ $evenement->titre }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-2">
                        <strong>📅 Date :</strong> {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-500 mb-4">
                        <strong>📍 Lieu :</strong> {{ $evenement->lieu }}
                    </p>

                    <!-- Statut de l'événement -->
                    <span class="px-3 py-1 text-white text-sm font-semibold rounded-lg
                        @if($evenement->statut === 'ouvert') bg-green-500
                        @elseif($evenement->statut === 'fermé') bg-gray-500
                        @else bg-red-500 @endif">
                        {{ ucfirst($evenement->statut) }}
                    </span>

                    <!-- Vérifier si l'utilisateur est inscrit à l'événement -->
                    @php
                        $estInscrit = auth()->check() && optional($evenement->participations)->contains('user_id', auth()->id());
                    @endphp

                    <!-- Boutons d’action -->
                    <div class="flex flex-wrap space-x-2 mt-3">
                        <a
                            href="{{ route('evenements.show', $evenement) }}"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition shadow-md"
                        >
                            ℹ️ Détails
                        </a>

                        @auth
                            <!-- Bouton Modifier (visible seulement pour l'organisateur) -->
                            @if (auth()->id() === $evenement->user_id)
                                <a href="{{ route('evenements.edit', $evenement) }}"
                                   class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition shadow-md">
                                    ✏️ Modifier
                                </a>
                            @endif

                            <!-- Bouton Participer -->
                            @if (!$estInscrit)
                            @if(is_null($evenement->max_participants) || $evenement->participations->count() < $evenement->max_participants)
                                <form action="{{ route('participations.participer', $evenement) }}" method="POST">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition shadow-md">
                                        ✅ Participer
                                    </button>
                                </form>
                            @else
                                <!-- Si le nombre maximum de participants est atteint, on affiche un message -->
                                <span class="text-red-500 font-semibold">Événement complet</span>
                            @endif
                        @endif
                    
                        <!-- Bouton Se Retirer -->
                        @if ($estInscrit)
                            <form action="{{ route('participations.seRetirer', $evenement) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition shadow-md">
                                    ❌ Se retirer
                                </button>
                            </form>
                        @endif
                    @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8 text-center">
            {{ $evenements->links() }}
        </div>
    @endif
@endsection
