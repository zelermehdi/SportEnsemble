<div>
    <div class="chat-container bg-gray-100 p-4 rounded-lg max-h-96 overflow-y-auto">
        @forelse($messages as $msg)
            <div class="message p-2 rounded-lg mb-2 
                @if($msg->user_id == auth()->id()) 
                    text-right bg-green-100 
                @else 
                    bg-gray-200 
                @endif"
            >
                <strong>{{ $msg->user->name }}</strong> : {{ $msg->contenu }}
            </div>
        @empty
            <p class="text-gray-500 italic">Aucun message pour le moment. Soyez le premier à lancer la discussion !</p>
        @endforelse
    </div>

    <form wire:submit.prevent="sendMessage" class="mt-3 flex space-x-2">
        <input
            type="text"
            wire:model="message"
            placeholder="Écrire un message..."
            class="flex-1 p-2 border border-gray-300 rounded-lg focus:ring focus:ring-green-300"
        >
        @error('message')
            <span class="text-red-500">{{ $message }}</span>
        @enderror

        <button
            type="submit"
            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition"
        >
            Envoyer
        </button>
    </form>
</div>
