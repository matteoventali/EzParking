document.addEventListener('DOMContentLoaded', () => {
    const pricePerHour = 2.50; // Prezzo fisso letto dal garage-info
    const totalCostEl = document.getElementById('totalCost');
    const slotCheckboxes = document.querySelectorAll('input[name="time_slot[]"]');
    const bookingForm = document.getElementById('bookingForm');

    function calculateTotalCost() {
        let totalDuration = 0;

        // Itera su tutte le checkbox per trovare quelle selezionate e non disabilitate
        slotCheckboxes.forEach(checkbox => {
            if (checkbox.checked && !checkbox.disabled) {
                const duration = parseFloat(checkbox.getAttribute('data-duration') || 1);
                totalDuration += duration;
            }
        });

        const totalCost = totalDuration * pricePerHour;

        // Formatta il testo di visualizzazione
        const durationText = (totalDuration === 1) ? '1 hour' : `${totalDuration} hours`;

        // Aggiorna l'elemento in pagina
        totalCostEl.textContent = `Total cost: €${totalCost.toFixed(2)} (${durationText})`;
    }

    // Setup Iniziale
    calculateTotalCost();

    // Gestione selezione slot: solo uno slot selezionabile
    slotCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                // Deseleziona tutti gli altri
                slotCheckboxes.forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                        cb.nextElementSibling.classList.remove('selected');
                    }
                });
                // Aggiungi la classe selected al pill
                checkbox.nextElementSibling.classList.add('selected');
            } else {
                // Rimuovi la classe selected se deselezionato
                checkbox.nextElementSibling.classList.remove('selected');
            }

            calculateTotalCost();
        });
    });

    // Gestione Invio Modulo
    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        const selectedSlots = document.querySelectorAll('input[name="time_slot[]"]:checked');

        if (selectedPayment && selectedSlots.length > 0) {
            let redirectUrl = '';
            const baseUrl = '../RES/payment/';

            // Determina l'URL di reindirizzamento
            switch (selectedPayment.value) {
                case 'applepay':
                case 'googlepay':
                case 'paypal':
                case 'creditcard':
                    redirectUrl = baseUrl + selectedPayment.value + '.php';
                    break;
                default:
                    this.submit();
                    return;
            }

            // Prepara i dati per l'URL di reindirizzamento
            const formData = new FormData(this);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                params.append(key, value);
            }

            // Calcola il costo finale e lo aggiunge come parametro
            let finalDuration = 0;
            selectedSlots.forEach(checkbox => {
                finalDuration += parseFloat(checkbox.getAttribute('data-duration') || 1);
            });
            const totalCostValue = (finalDuration * pricePerHour).toFixed(2);
            params.append('calculated_cost', totalCostValue);

            // Reindirizza l'utente
            window.location.href = `${redirectUrl}?${params.toString()}`;

        } else {
            alert('Please select at least one time slot and a payment method.');
        }
    });

});
