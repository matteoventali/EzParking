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
