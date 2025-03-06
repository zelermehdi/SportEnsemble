@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-bold mb-6 text-green-700 text-center">🛠 Gestion des événements (Admin)</h2>

<!-- Table container -->
<div class="bg-white shadow-lg rounded-xl p-6">
    @if($evenements->isEmpty())
        <p class="text-gray-600 text-center">📌 Aucun événement trouvé.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-200 rounded-lg shadow-md">
                <thead class="bg-green-200">
                    <tr class="text-left text-gray-700">
                        <th class="p-4 font-semibold">📌 Titre</th>
                        <th class="p-4 font-semibold">📅 Date</th>
                        <th class="p-4 font-semibold text-center">⚙️ Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($evenements as $evenement)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="p-4 font-medium text-gray-800">{{ $evenement->titre }}</td>
                        <td class="p-4 text-gray-600">
                            {{ \Carbon\Carbon::parse($evenement->date)->format('d/m/Y H:i') }}
                        </td>
                        <td class="p-4 flex justify-center items-center space-x-2">
                            <!-- Bouton Supprimer avec Alpine.js -->
                            <div x-data="{ confirmDelete: false }">
                                <button
                                    @click="confirmDelete = true"
                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 focus:ring-2 focus:ring-red-400 transition-all"
                                >
                                    ❌ Supprimer
                                </button>

                                <!-- Modale de confirmation -->
                                <div x-show="confirmDelete" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50">
                                    <div class="bg-white p-6 rounded-xl shadow-lg max-w-md">
                                        <p class="text-lg font-semibold text-gray-800 text-center mb-4">
                                            ❗ Êtes-vous sûr de vouloir supprimer<br> 
                                            <span class="text-red-600 font-bold">{{ $evenement->titre }}</span> ?
                                        </p>
                                        <div class="flex justify-center space-x-4">
                                            <button
                                                type="button"
                                                @click="confirmDelete = false"
                                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition"
                                            >
                                                🔙 Annuler
                                            </button>
                                            <form action="{{ route('admin.evenements.supprimer', $evenement) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition"
                                                >
                                                    ✅ Confirmer
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
        </div>
    @endif
</div>
@endsection
