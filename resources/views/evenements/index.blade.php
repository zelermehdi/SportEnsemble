@extends('layouts.app')

@section('content')
    <h2 class="text-3xl font-bold mb-6 text-green-700">Liste des événements sportifs</h2>

    <!-- Formulaire de filtrage -->
    <div class="bg-green-50 shadow-md rounded-lg p-6 mb-8">
        <form method="GET" action="{{ route('evenements.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <input
                type="text"
                name="q"
                placeholder="Rechercher un événement..."
                value="{{ request('q') }}"
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
            >

            <select
                name="type_sport"
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
            >
                <option value="all">Tous les sports</option>
                <option value="foot"   @selected(request('type_sport') === 'foot')>Football</option>
                <option value="course" @selected(request('type_sport') === 'course')>Course</option>
                <option value="basket" @selected(request('type_sport') === 'basket')>Basket</option>
                <option value="autre"  @selected(request('type_sport') === 'autre')>Autre</option>
            </select>

            <input
                type="text"
                name="lieu"
                placeholder="Lieu"
                value="{{ request('lieu') }}"
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
            >

            <input
                type="date"
                name="date"
                value="{{ request('date') }}"
                class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
            >

            <button
                type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition w-full md:w-auto"
            >
                Filtrer
            </button>
        </form>
    </div>

    <!-- Bouton de création d’événement -->
    <div class="mb-6">
        <a
            href="{{ route('evenements.create') }}"
            class="bg-green-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-green-600 transition inline-block"
        >
            Créer un événement
        </a>
    </div>

    <!-- Liste des événements -->
    @if($evenements->isEmpty())
        <p class="text-gray-600">Aucun événement trouvé.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($evenements as $evenement)
                <div class="bg-white shadow-md rounded-lg p-4 transition transform hover:scale-105">
                    <h3 class="text-xl font-semibold text-green-700 mb-2">
                        {{ $evenement->titre }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-1">
                        <strong>Date : </strong>
                        {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <strong>Lieu : </strong> {{ $evenement->lieu }}
                    </p>

                    <!-- Boutons d’action -->
                    <div class="flex flex-wrap space-x-2">
                        <a
                            href="{{ route('evenements.show', $evenement) }}"
                            class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition"
                        >
                            Détails
                        </a>

                        <form action="{{ route('participations.participer', $evenement) }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 transition"
                            >
                                Participer
                            </button>
                        </form>

                        <form action="{{ route('participations.seRetirer', $evenement) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition"
                            >
                                Se retirer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $evenements->links() }}
        </div>
    @endif
@endsection
