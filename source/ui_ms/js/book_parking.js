document.addEventListener('DOMContentLoaded', () => {
    const totalCostEl = document.getElementById('totalCost');
    const bookingForm = document.getElementById('bookingForm');
    const dateInput = document.getElementById("date");
    const slotContainer = document.getElementById("slotOptionsContainer");
    const prevDayBtn = document.getElementById("prevDay");
    const nextDayBtn = document.getElementById("nextDay");

    function updateSlots(date) 
    {
        const slots = slotData[date];
        slotContainer.innerHTML = "";

        if (!slots || slots.length === 0) 
        {
            const msg = document.createElement("div");
            msg.className = "no-slots-message";
            msg.textContent = "No parking slot available";
            slotContainer.appendChild(msg);
            updateTotalCost();
            return;
        }

        slots.forEach((slot, index) => 
        {
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

    function updateTotalCost() 
    {
        const selectedSlots = document.querySelectorAll('input[name="time_slot[]"]:checked');
        
        if (selectedSlots.length === 0) {
            totalCostEl.textContent = `Total cost: €0.00`;
            return;
        }
        
        const hours = selectedSlots[0].dataset.duration ? parseInt(selectedSlots[0].dataset.duration) : 0;
        const cost = hours * pricePerHour;

        totalCostEl.textContent = hours > 0
            ? `Total cost: €${cost.toFixed(2)} (${hours} hour${hours !== 1 ? "s" : ""})`
            : `Total cost: €0.00`;
    }

    function changeDate(days) 
    {
        const date = new Date(dateInput.value);
        date.setDate(date.getDate() + days);
        const newDateStr = date.toISOString().split('T')[0];
        dateInput.value = newDateStr;
        updateSlots(newDateStr);
    }

    prevDayBtn.addEventListener("click", () => changeDate(-1));
    nextDayBtn.addEventListener("click", () => changeDate(1));
    dateInput.addEventListener("change", () => updateSlots(dateInput.value));

    bookingForm.addEventListener('submit', function(event) {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        const selectedSlot = document.querySelector('input[name="time_slot[]"]:checked');
        const plate = document.getElementById('plate').value.trim();
        const date = document.getElementById('date').value.trim();

        if (!selectedPayment || !selectedSlot || plate === "" || date === "" || plate.length != 7 ) {
            event.preventDefault();
            alert("Please fill in all required fields (date, slot, plate, and payment method).");
        }
    });

    updateSlots(dateInput.value);
});
