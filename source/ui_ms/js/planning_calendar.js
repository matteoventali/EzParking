reservations = [
  {
    "id": "r1",
    "title": "Prenotazione Parking A",
    "start": "2025-11-26T09:00",
    "end": "2025-11-26T11:00"
  },
  {
    "id": "r2",
    "title": "Prenotazione Parking B",
    "start": "2025-11-27T14:00",
    "end": "2025-11-27T16:00"
  },
  {
    "id": "r3",
    "title": "Prenotazione Parking C",
    "start": "2025-11-28T10:00",
    "end": "2025-11-28T12:00"
  }
];
freeSlots = [
  {
    "id": "free-1",
    "title": "Libero - Parking A",
    "start": "2025-11-29T08:00",
    "end": "2025-11-29T12:00"
  },
  {
    "id": "free-2",
    "title": "Libero - Parking B",
    "start": "2025-11-30T09:00",
    "end": "2025-11-30T13:00"
  },
  {
    "id": "free-3",
    "title": "Libero - Parking A",
    "start": "2025-12-01T15:00",
    "end": "2025-12-01T18:00"
  }
]
;
busySlots = [
  {
    "id": "busy-1",
    "title": "Occupato - Parking A",
    "start": "2025-11-26T12:00",
    "end": "2025-11-26T14:00"
  },
  {
    "id": "busy-2",
    "title": "Occupato - Parking B",
    "start": "2025-11-27T09:00",
    "end": "2025-11-27T11:00"
  },
  {
    "id": "busy-3",
    "title": "Occupato - Parking C",
    "start": "2025-11-28T13:00",
    "end": "2025-11-28T16:00"
  }
]
;

document.addEventListener("DOMContentLoaded", function () 
{
    const calendarEl = document.getElementById("calendar");

    const calendar = new FullCalendar.Calendar(calendarEl, 
    {
        initialView: "dayGridMonth",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,listWeek"
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false,
            hour12: false
        },
        displayEventEnd: true,
        windowResize: () => updateView(),      
        eventSources: [
        {
            events: reservations,
            color: "#4d94ff",
            textColor: "#fff"
        },
        {
            events: freeSlots,
            color: "#c2f7c2",
            textColor: "#000"
        },
        {
            events: busySlots,
            color: "#ff4d4d",
            textColor: "#fff"
        }
        ],

        eventDisplay: "block",
        eventOrder: "start"
    });

    function updateView(){
        const width = window.innerWidth;

        if (width < 430) {
            calendar.changeView('listWeek');
        }
        else if (width < 600) {
            calendar.changeView('timeGridWeek');
        }
        else {
            calendar.changeView('dayGridMonth');
        }      
    }
    calendar.render();
});
