document.addEventListener("DOMContentLoaded", function () 
{
    const calendarEl = document.getElementById("calendar");

    const calendar = new FullCalendar.Calendar(calendarEl, 
    {
        //initialView: "dayGridMonth",
        initialView: "listWeek",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "listDay,listMonth,listWeek"
        },
        buttonText: {
            listDay: "Day",
            listWeek: "Week",
            listMonth: "Month"
        },        
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: false,
            hour12: false
        },
        displayEventEnd: true,
        //windowResize: () => updateView(),      
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
        eventOrder: "start",

        eventClick: function(info) {
            openEventPopup(info.event);
        }
    });

    /*function updateView(){
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
    }*/
    calendar.render();

    // Event popup
    const eventModal = document.getElementById("eventModal");
    const closeEventBtn = document.getElementById("eventCloseBtn");

    const popupTitle = document.getElementById("popupTitle");
    const popupDate = document.getElementById("popupDate");
    const popupStart = document.getElementById("popupStart");
    const popupEnd = document.getElementById("popupEnd");

    const popupResidentWrapper = document.getElementById("popupResidentWrapper");
    const popupResident = document.getElementById("popupResident");
    const popupDriverWrapper = document.getElementById("popupDriverWrapper");
    const popupDriver = document.getElementById("popupDriver");
    const popupPlateWrapper = document.getElementById("popupPlateWrapper");
    const popupPlate = document.getElementById("popupPlate");
    const popupStatusWrapper = document.getElementById("popupStatusWrapper");
    const popupStatus = document.getElementById("popupStatus");
    const deleteFreeSlotBtn = document.getElementById("deleteFreeSlotBtn");
    const popupMapWrapper = document.getElementById("popupMapWrapper");
    const popupMapLink = document.getElementById("popupMapLink");
    const popupManageWrapper = document.getElementById("popupManageWrapper");
    const popupManageLink = document.getElementById("popupManageLink");

    function openEventPopup(event) 
    {
        const title = event.title;
        const start = event.start;
        const end = event.end;

        // Reset dynamic sections
        popupResidentWrapper.style.display = "none";
        popupStatusWrapper.style.display = "none";
        deleteFreeSlotBtn.style.display = "none";

        let type = "";
        let parkingName = "";

        if (title.startsWith("Free slot")) 
        {
            type = "Free slot";
            parkingName = title.replace("Free slot for ", "");
        }
        else if (title.startsWith("Reserved slot")) 
        {
            type = "Reserved slot";
            parkingName = title.replace("Reserved slot for ", "");
        }
        else if (title.startsWith("Reservation")) 
        {
            type = "Reservation";
            parkingName = title.replace("Reservation for ", "");
        }

        popupTitle.textContent = `${type} for ${parkingName}`;
        popupDate.textContent = start.toISOString().split("T")[0];
        popupStart.textContent = start.toTimeString().substring(0,5);
        popupEnd.textContent = end ? end.toTimeString().substring(0,5) : "--";

        // Only for reservation
        if (type === "Reservation") {
            popupResidentWrapper.style.display = "block";
            popupResident.textContent = event.extendedProps.resident;

            popupStatusWrapper.style.display = "block";
            popupStatus.textContent = event.extendedProps.status;

            popupPlateWrapper.style.display = "block";
            popupPlate.textContent = event.extendedProps.plate;

            
            if ( event.extendedProps.status === "CONFIRMED" || event.extendedProps.status === "COMPLETED" )
            {
                popupMapWrapper.style.display = "block";

                const lat = event.extendedProps.latitude;
                const lon = event.extendedProps.longitude;

                popupMapLink.href = `https://www.google.com/maps?q=${lat},${lon}`;
            }
        }

        // Only for reserved slot
        if (type === "Reserved slot") {
            popupDriverWrapper.style.display = "block";
            popupDriver.textContent = event.extendedProps.driver;

            popupStatusWrapper.style.display = "block";
            popupStatus.textContent = event.extendedProps.status;

            popupPlateWrapper.style.display = "block";
            popupPlate.textContent = event.extendedProps.plate;

            if ( event.extendedProps.status === "PENDING" )
            {
                popupManageWrapper.style.display = "block";
                popupManageLink.href = "manage_request.php";
            }
            else
            {
                popupManageWrapper.style.display = "none";
                popupManageLink.href = "#";
            }
        }

        // Only for free slot
        if (type === "Free slot") {
            deleteFreeSlotBtn.style.display = "inline-block";
            deleteFreeSlotBtn.onclick = function () 
            {
                // Send a request to delete the free slot
                xhr = new XMLHttpRequest();
                xhr.open("POST", "../php/delete_free_slot.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if ( xhr.status === 200 ) {
                        // Parsing the response
                        const response = JSON.parse(xhr.responseText);
                        if (response.body.code === "0") {
                                // Close the popup
                                closeEventPopup();
                                // Remove the event from the calendar
                                event.remove();
                        } else {
                            alert("Error: " + response.body.desc);
                        }
                    }
                };
                xhr.send("slot_id=" + encodeURIComponent(event.id));
            };
        }

        eventModal.classList.add("active");
    }

    function closeEventPopup() {
        eventModal.classList.remove("active");
    }

    closeEventBtn.addEventListener("click", closeEventPopup);
    document.querySelector("#eventModal .modal-backdrop")
            .addEventListener("click", closeEventPopup);
});


