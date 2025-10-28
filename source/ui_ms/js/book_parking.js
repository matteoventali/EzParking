document.addEventListener('DOMContentLoaded', () => {
    const pricePerHour = 2.50;
    const totalCostEl = document.getElementById('totalCost');
    const bookingForm = document.getElementById('bookingForm');
    const dateInput = document.getElementById("date");
    const slotContainer = document.getElementById("slotOptionsContainer");
    const prevDayBtn = document.getElementById("prevDay");
    const nextDayBtn = document.getElementById("nextDay");


    const slotData = {
        "2025-10-28": [
            { time: "08:00-09:00", price: 2.5 },
            { time: "09:00-10:00", price: 2.5 },
            { time: "11:00-12:00", price: 2.5 },
            { time: "12:00-13:00", price: 2.5 },
            { time: "13:00-14:00", price: 2.5 },
            { time: "14:00-15:00", price: 2.5 },
            { time: "15:00-16:00", price: 2.5 }
        ],
        "2025-10-29": [
            { time: "09:00-10:00", price: 2.5 },
            { time: "10:00-11:00", price: 2.5 },
            { time: "12:00-13:00", price: 2.5 },
            { time: "13:00-14:00", price: 2.5 },
            { time: "14:00-15:00", price: 2.5 },
            { time: "15:00-16:00", price: 2.5 }
        ],
        "2025-10-30": [
            { time: "08:00-09:00", price: 2.5 },
            { time: "09:00-10:00", price: 2.5 },
            { time: "10:00-11:00", price: 2.5 },
            { time: "11:00-12:00", price: 2.5 },
            { time: "13:00-14:00", price: 2.5 },
            { time: "14:00-15:00", price: 2.5 },
            { time: "15:00-16:00", price: 2.5 }
        ],
        "2025-10-31": [] // Nessuno slot disponibile
    };

    function updateSlots(date) {
        const slots = slotData[date];
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
            input.value = slot.time;
            input.dataset.duration = 1;

            const label = document.createElement("label");
            label.htmlFor = slotId;
            label.className = "slot-pill";
            label.textContent = `${slot.time} (€${slot.price})`;

            // Solo uno slot selezionabile
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
        const hours = selectedSlots.length;
        const cost = hours * pricePerHour;

        totalCostEl.textContent = hours > 0
            ? `Total cost: €${cost.toFixed(2)} (${hours} hour${hours !== 1 ? "s" : ""})`
            : `Total cost: €0.00`;
    }

    function changeDate(days) {
        const date = new Date(dateInput.value);
        date.setDate(date.getDate() + days);
        const newDateStr = date.toISOString().split('T')[0];
        dateInput.value = newDateStr;
        updateSlots(newDateStr);
    }

    prevDayBtn.addEventListener("click", () => changeDate(-1));
    nextDayBtn.addEventListener("click", () => changeDate(1));
    dateInput.addEventListener("change", () => updateSlots(dateInput.value));

    // GESTIONE PAGAMENTO E INVIO FORM
    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        const selectedSlots = document.querySelectorAll('input[name="time_slot[]"]:checked');

        if (selectedPayment && selectedSlots.length > 0) {
            const baseUrl = '../RES/payment/';
            const redirectUrl = baseUrl + selectedPayment.value + '.php';

            const formData = new FormData(this);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            let finalDuration = 0;
            selectedSlots.forEach(cb => {
                finalDuration += parseFloat(cb.getAttribute('data-duration') || 1);
            });

            const totalCostValue = (finalDuration * pricePerHour).toFixed(2);
            params.append('calculated_cost', totalCostValue);

            window.location.href = `${redirectUrl}?${params.toString()}`;
        } else {
            alert('Please select a time slot and payment method.');
        }
    });
    updateSlots(dateInput.value);
});
