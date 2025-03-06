<div id="calendar-container">
    <!-- En-tête avec navigation -->
    <div id="calendar-header" class="flex justify-between items-center mb-4">
        <button id="prev-month" class="bg-gray-300 px-2 py-1 rounded">Précédent</button>
        <span id="month-year" class="font-bold"></span>
        <button id="next-month" class="bg-gray-300 px-2 py-1 rounded">Suivant</button>
    </div>
    <!-- Tableau du calendrier -->
    <table id="calendar" class="w-full border-collapse">
      <thead>
         <tr>
             <th class="border p-2">Lundi</th>
             <th class="border p-2">Mardi</th>
             <th class="border p-2">Mercredi</th>
             <th class="border p-2">Jeudi</th>
             <th class="border p-2">Vendredi</th>
             <th class="border p-2">Samedi</th>
             <th class="border p-2">Dimanche</th>
         </tr>
      </thead>
      <tbody id="calendar-body"></tbody>
    </table>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Récupération des événements depuis le composant Livewire
    var events = @json($evenements);
    console.log("Events:", events);

    // Variables pour gérer le calendrier
    let currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth(); // 0-indexé (0 = janvier)

    const monthNames = [
        "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
        "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"
    ];

    // Fonction pour construire le calendrier pour un mois donné
    function renderCalendar(year, month) {
        const calendarBody = document.getElementById('calendar-body');
        calendarBody.innerHTML = ''; // Réinitialiser le corps du calendrier

        // Mettre à jour l'en-tête
        document.getElementById('month-year').textContent = monthNames[month] + " " + year;

        // Premier jour du mois
        let firstDay = new Date(year, month, 1);
        let startDay = firstDay.getDay();
        // Ajustement : en JavaScript, 0 = dimanche. Pour un calendrier commençant par lundi, traitons 0 comme 7.
        if (startDay === 0) startDay = 7;

        // Nombre de jours dans le mois
        let daysInMonth = new Date(year, month + 1, 0).getDate();

        // Création de la première ligne
        let row = document.createElement('tr');
        // Cellules vides avant le premier jour
        for (let i = 1; i < startDay; i++) {
            let emptyCell = document.createElement('td');
            emptyCell.className = "border p-2";
            row.appendChild(emptyCell);
        }

        // Remplissage des jours
        for (let day = 1; day <= daysInMonth; day++) {
            let cell = document.createElement('td');
            cell.className = "border p-2 align-top";
            let cellDate = new Date(year, month, day);
            let cellDateStr = cellDate.toISOString().split('T')[0]; // format "YYYY-MM-DD"

            // Ajout du numéro du jour
            let dayDiv = document.createElement('div');
            dayDiv.textContent = day;
            dayDiv.className = "font-bold mb-1";
            cell.appendChild(dayDiv);

            // Vérifier s'il y a des événements pour cette date
            events.forEach(function(event) {
                // Comparer la date de l'événement (en ignorant l'heure)
                let eventDate = new Date(event.start);
                let eventDateStr = eventDate.toISOString().split('T')[0];
                if (eventDateStr === cellDateStr) {
                    let eventDiv = document.createElement('div');
                    eventDiv.innerHTML = `<a href="${event.url}" style="color:${event.color}; font-size:0.9em;">${event.title}</a>`;
                    cell.appendChild(eventDiv);
                }
            });

            row.appendChild(cell);

            // Lorsque la ligne contient 7 cellules, l'ajouter au corps du tableau et créer une nouvelle ligne
            if (row.children.length === 7) {
                calendarBody.appendChild(row);
                row = document.createElement('tr');
            }
        }

        // Compléter la dernière ligne avec des cellules vides si nécessaire
        if (row.children.length > 0) {
            while (row.children.length < 7) {
                let emptyCell = document.createElement('td');
                emptyCell.className = "border p-2";
                row.appendChild(emptyCell);
            }
            calendarBody.appendChild(row);
        }
    }

    // Navigation
    document.getElementById('prev-month').addEventListener('click', function () {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentYear, currentMonth);
    });

    document.getElementById('next-month').addEventListener('click', function () {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentYear, currentMonth);
    });

    // Afficher le calendrier du mois en cours
    renderCalendar(currentYear, currentMonth);
});
</script>
@endpush
