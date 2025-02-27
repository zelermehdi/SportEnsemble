<div class="flex flex-col h-[80vh] max-w-2xl mx-auto">
    <!-- Conteneur du chat -->
    <div class="flex-1 overflow-y-auto bg-gray-100 p-4 rounded-lg shadow-md" id="chatBox">
        @forelse($messages as $msg)
            <div class="flex items-start @if($msg->user_id == auth()->id()) justify-end @endif mb-2">
                <div class="p-3 max-w-[75%] rounded-lg shadow-md
                    @if($msg->user_id == auth()->id()) bg-green-500 text-white @else bg-white border @endif">
                    <strong class="block text-sm">{{ $msg->user->name }}</strong>
                    <p class="text-sm">{{ $msg->contenu }}</p>
                    <span class="text-xs text-gray-500 mt-1 block text-right">
                        {{ \Carbon\Carbon::parse($msg->created_at)->format('d/m/Y H:i') }}
                    </span>
                </div>
            </div>
        @empty
            <p class="text-gray-500 italic text-center">Aucun message pour le moment. Soyez le premier à parler !</p>
        @endforelse
    </div>

    <!-- "En train d'écrire..." -->
    <div class="text-sm text-gray-500 italic px-4 py-2 hidden" id="typingIndicator">
        ✍️ <span id="typingUser"></span> est en train d'écrire...
    </div>

    <!-- Formulaire d'envoi -->
    <form wire:submit.prevent="sendMessage" class="mt-3 flex items-center space-x-2">
        <input
            type="text"
            wire:model="message"
            placeholder="Écrire un message..."
            class="flex-1 p-3 border border-gray-300 rounded-lg focus:ring focus:ring-green-300 transition shadow-md"
            id="messageInput"
        >
        <button
            type="submit"
            class="bg-green-500 text-white px-4 py-3 rounded-lg shadow-md hover:bg-green-600 transition flex items-center space-x-2"
            wire:loading.attr="disabled"
        >
            <span>Envoyer</span>
            <svg class="w-5 h-5 animate-spin hidden" id="sendLoader" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
    </form>
</div>

@push('scripts')
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("✅ Pusher chargé...");

        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        var channel = pusher.subscribe("chat-evenement.{{ $evenementId }}");

        channel.bind("MessageEnvoye", function (data) {
            console.log(`💬 Nouveau message de ${data.user} : ${data.contenu}`);

            // 🔥 Mettre à jour Livewire
            Livewire.dispatch("refreshChat");

            // Scroll vers le bas du chat
            setTimeout(() => {
                let chatBox = document.getElementById('chatBox');
                if (chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            }, 500);
        });

        // Typing Indicator
        let typingTimer;
        let typingUser = document.getElementById("typingUser");
        let typingIndicator = document.getElementById("typingIndicator");

        document.getElementById("messageInput").addEventListener("input", function () {
            clearTimeout(typingTimer);
            channel.trigger("client-typing", { user: "{{ auth()->user()->name }}" });

            typingTimer = setTimeout(() => {
                channel.trigger("client-stop-typing", {});
            }, 3000);
        });

        channel.bind("client-typing", function (data) {
            typingUser.textContent = data.user;
            typingIndicator.classList.remove("hidden");
        });

        channel.bind("client-stop-typing", function () {
            typingIndicator.classList.add("hidden");
        });

        // Bouton d'envoi : Ajouter une animation de chargement
        document.querySelector("form").addEventListener("submit", function () {
            document.getElementById("sendLoader").classList.remove("hidden");
            setTimeout(() => {
                document.getElementById("sendLoader").classList.add("hidden");
            }, 1000);
        });
    });

    // Scroll automatique à chaque mise à jour Livewire
    Livewire.hook('message.processed', (message, component) => {
        let chatBox = document.getElementById('chatBox');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    });
</script>
@endpush
