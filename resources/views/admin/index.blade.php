@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6 text-green-700">Gestion des événements (Admin)</h2>

<!-- Table style -->
<div class="bg-white shadow-md rounded-lg p-4">
    @if($evenements->isEmpty())
        <p class="text-gray-600">Aucun événement trouvé.</p>
    @else
        <table class="w-full border-collapse border border-gray-200">
            <thead class="bg-green-100">
                <tr>
                    <th class="p-3 text-left font-semibold text-gray-700">Titre</th>
                    <th class="p-3 text-left font-semibold text-gray-700">Date</th>
                    <th class="p-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($evenements as $evenement)
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="p-3">{{ $evenement->titre }}</td>
                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
                    </td>
                    <td class="p-3">
                        <div x-data="{ confirmDelete: false }" class="inline-block">
                            <!-- Bouton supprimer -->
                            <button
                                @click="confirmDelete = true"
                                class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition"
                            >
                                Supprimer
                            </button>

                            <!-- Fenêtre modale de confirmation -->
                            <div
                                x-show="confirmDelete"
                                class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50"
                            >
                                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm">
                                    <p class="text-lg mb-4 text-gray-800">
                                        Êtes-vous sûr de vouloir supprimer l'événement
                                        <strong>{{ $evenement->titre }}</strong> ?
                                    </p>
                                    <div class="flex justify-end space-x-4">
                                        <button
                                            type="button"
                                            @click="confirmDelete = false"
                                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400"
                                        >
                                            Annuler
                                        </button>
                                        <form
                                            action="{{ route('admin.evenements.supprimer', $evenement) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600"
                                            >
                                                Confirmer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- / x-data -->
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
