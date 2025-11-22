function classifyEvents(spots, reservations, availabilities, currentUserId) {
    const bookedByOthers = [];
    const myBookings = [];
    const freeSpots = [];

    spots.forEach(spot => {
        //Find availabilities slots for spot
        const spotAvailabilities = availabilities.filter(a => a.spotId === spot.id);
        //Find reservations for spot
        const spotReservations = reservations.filter(r => r.spotId === spot.id);

        if (spot.ownerId === currentUserId) {
            spotAvailabilities.forEach(av => {
            //Check to see if the spot is reserve
            const reservationInSlot = spotReservations.find(r =>
                r.start >= av.start && r.end <= av.end
            );

        if (reservationInSlot) {
          //if the spot is reserved
          if (reservationInSlot.userId !== currentUserId) {
            bookedByOthers.push({
              id: reservationInSlot.id,
              title: `${spot.name}`,
              start: reservationInSlot.start,
              end: reservationInSlot.end,              
              color: "#ff4d4d",
              textColor: "#fff"
            });
          }
        } else {
          //Free slots for the spot
          freeSpots.push({
            id: `free-${spot.id}-${av.id}`,
            title: `Free ${spot.name}`,
            start: av.start,
            end: av.end,
            color: "#c2f7c2",
            textColor: "#000"
          });
        }
      });
    } else {
      //Parking spot is not property of the current user, then check if there is a reservation in the users' name
      spotReservations.forEach(r => {
        if (r.userId === currentUserId) {
            myBookings.push({
            id: r.id,
            title: ` ${spot.name}`,
            start: r.start,
            end:r.end,
            color: "#4d94ff",
            textColor: "#fff"
            });
        }
        });
    }
    });

    return { bookedByOthers, myBookings, freeSpots };
}

document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");

  const parkingSpots = [
    { id: 1, name: "Parking 1", ownerId: 10 },
    { id: 2, name: "Parking 2", ownerId: 10 },
    { id: 3, name: "Parking 3", ownerId: 20 }
  ];

  const reservations = [
    { id: "r1", spotId: 1, userId: 30, start: "2025-11-26T16:00", end: "2025-11-26T20:00" },
    { id: "r2", spotId: 2, userId: 40, start: "2025-11-26T10:00", end: "2025-11-26T12:00" },
    { id: "r3", spotId: 3, userId: 10, start: "2025-11-28T09:00", end: "2025-11-28T11:00" }
  ];

  const availabilities = [
    { id: "a1", spotId: 1, start: "2025-11-26T16:00", end: "2025-11-26T20:00" },
    { id: "a2", spotId: 2, start: "2025-11-26T10:00", end: "2025-11-26T12:00" },
    { id: "a3", spotId: 2, start: "2025-11-29T09:00", end: "2025-11-29T12:00" }
  ];

  const currentUserId = 10;

  const { bookedByOthers, myBookings, freeSpots } =
    classifyEvents(parkingSpots, reservations, availabilities, currentUserId);

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,listWeek"
    },
    eventTimeFormat: {
      hour: '2-digit',
      minute: '2-digit',
      meridiem: false
    },
    displayEventEnd: true,
    windowResize: function(view) {
      if (window.innerWidth < 600) {
        calendar.changeView('timeGridWeek');
      } else {
        calendar.changeView('dayGridMonth');
      }
    },      
    eventSources: [
      { events: bookedByOthers },
      { events: myBookings },    
      { events: freeSpots }       
    ],
    eventDisplay: "block",
    eventOrder: "start",
  });

  calendar.render();
});
