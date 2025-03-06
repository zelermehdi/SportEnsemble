import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import toastr from "toastr"; // ✅ Import correct de Toastr
import "toastr/build/toastr.min.css"; // ✅ Import du CSS de Toastr
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

// Vérifier que Toastr est bien chargé
if (typeof toastr !== "undefined") {
    console.log("✅ Toastr chargé :", toastr);
} else {
    console.error("❌ Toastr ne s'est pas chargé correctement !");
}

// 🎯 Configurer Toastr.js
toastr.options = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-bottom-right",
    timeOut: 3000,
};

// 🔥 Écouter les notifications pour l'utilisateur connecté
if (window.Laravel.userId) {
    window.Echo.private(`notifications.${window.Laravel.userId}`)
        .listen('.NotificationSent', (e) => {
            console.log("🔔 Nouvelle notification :", e);
            toastr.success("Nouvelle notification reçue !");
            Livewire.emit('notificationRecu');
        });
}

// 🔥 Écouter les nouveaux événements
window.Echo.channel('evenements')
    .listen('.EvenementCree', (e) => {
        console.log("📅 Nouvel événement créé :", e);
        toastr.success("Nouvel événement ajouté : " + e.evenement.titre);
    });


    // document.addEventListener('DOMContentLoaded', function () {
    //     let calendarEl = document.getElementById('calendar');
    //     if (calendarEl) {
    //         let calendar = new Calendar(calendarEl, {
    //             plugins: [dayGridPlugin, interactionPlugin],
    //             initialView: 'dayGridMonth',
    //             events: '/evenements/calendrier', // Route pour récupérer les événements
    //         });
    //         calendar.render();
    //     }
    // });