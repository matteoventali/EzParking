document.addEventListener("DOMContentLoaded", function () {
    const slotsData = window.slots || {};
    const availableDates = Object.keys(slotsData).sort();

    const dateInput = document.getElementById("date");
    const prevDayBtn = document.getElementById("prevDay");
    const nextDayBtn = document.getElementById("nextDay");
    const slotContainer = document.getElementById("slotOptionsContainer");
    const totalCostEl = document.getElementById("totalCost");
    const bookingForm = document.getElementById("bookingForm");

    if (availableDates.length === 0) {
        if (dateInput) dateInput.setAttribute("disabled", true);
        return;
    }

    dateInput.setAttribute("min", availableDates[0]);
    dateInput.setAttribute("max", availableDates[availableDates.length - 1]);
    dateInput.value = availableDates[0];

    const fpInstance = flatpickr("#date", {
        dateFormat: "Y-m-d",
        defaultDate: availableDates[0],
        enable: availableDates,
        minDate: availableDates[0],
        maxDate: availableDates[availableDates.length - 1],
        onDayCreate: function (dObj, dStr, fp, dayElem) {
            const date = fp.formatDate(dayElem.dateObj, "Y-m-d");
            if (availableDates.includes(date)) {
                dayElem.classList.add("slot-available");
            }
        },
        onChange: function (selectedDates, dateStr) {
            updateSlots(dateStr);
            currentIndex = availableDates.indexOf(dateStr);
        }
    });

    function updateSlots(date) {
        const slots = slotsData[date];
        slotContainer.innerHTML = "";

        if (!slots || slots.length === 0) {
            const msg = document.createElement("div");
            msg.className = "no-slots-message";
            msg.textContent = "No parking slot available";
            slotContainer.appendChild(msg);
            updateTotalCost();
            return;
        }

        slots.forEach((slot, index) => {
            const slotId = `slot-${index}`;

            const input = document.createElement("input");
            input.type = "checkbox";
            input.id = slotId;
            input.name = "time_slot[]";
            input.value = slot.id;
            input.dataset.duration = slot.duration;

            const label = document.createElement("label");
            label.htmlFor = slotId;
            label.className = "slot-pill";
            label.textContent = `${slot.time} (€${pricePerHour})`;

            input.addEventListener("change", () => {
                document.querySelectorAll('input[name="time_slot[]"]').forEach(el => {
                    if (el !== input) el.checked = false;
                });
                document.querySelectorAll('.slot-pill').forEach(l => l.classList.remove('selected'));
                if (input.checked) label.classList.add('selected');
                updateTotalCost();
            });

            slotContainer.appendChild(input);
            slotContainer.appendChild(label);
        });

        updateTotalCost();
    }

    function updateTotalCost() {
        const selectedSlots = document.querySelectorAll('input[name="time_slot[]"]:checked');

        if (selectedSlots.length === 0) {
            totalCostEl.textContent = `Total cost: €0.00`;
            return;
        }

        const hours = selectedSlots[0].dataset.duration ? selectedSlots[0].dataset.duration : 0;
        const cost = hours * pricePerHour;

        totalCostEl.textContent = hours > 0
            ? `Total cost: €${cost.toFixed(2)}`
            : `Total cost: €0.00`;
    }

    function changeDate(days) 
    {
        const dateInput = document.getElementById("date");
        const index = availableDates.indexOf(dateInput.value);        
        const newIndex = index + days;

        const newDate = availableDates[newIndex];

        if ( newDate != undefined ) 
        {
            dateInput.value = newDate;
            const newDateStr = new Date(newDate + "T00:00:00");
            if (fpInstance) fpInstance.setDate(newDateStr, true);        
            updateSlots(newDate);
        }
    }

    prevDayBtn.addEventListener("click", () => changeDate(-1));
    nextDayBtn.addEventListener("click", () => changeDate(1));
    dateInput.addEventListener("change", () => {
        const val = dateInput.value;
        if (availableDates.includes(val)) {
            currentIndex = availableDates.indexOf(val);
            updateSlots(val);
        }
    });

    bookingForm.addEventListener('submit', function (event) {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        const selectedSlot = document.querySelector('input[name="time_slot[]"]:checked');
        const plate = document.getElementById('plate').value.trim();
        const date = document.getElementById('date').value.trim();

        if (!selectedPayment || !selectedSlot || plate === "" || date === "" || plate.length != 7) {
            event.preventDefault();
            alert("Please fill in all required fields (date, slot, plate, and payment method).");
        }
    });

    updateSlots(dateInput.value);
});
