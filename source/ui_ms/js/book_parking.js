
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

    // 3. Setup Iniziale
    // Inizializza il costo a 0.00 all'avvio della pagina
    calculateTotalCost();
    // Assicura che la visualizzazione sia corretta anche se non viene selezionato nulla

    // 4. Aggiungi Event Listener ad ogni checkbox
    slotCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotalCost);
    });

    // 5. Gestione Invio Modulo
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
                    // Se il metodo non è gestito, invia il form all'azione predefinita
                    this.submit();
                    return;
            }

            // Prepara i dati per l'URL di reindirizzamento
            const formData = new FormData(this);
            const params = new URLSearchParams();

            // Copia tutti i dati del form nell'oggetto URLSearchParams
            for (let [key, value] of formData.entries()) {
                 // FormData gestisce già gli array (time_slot[]) correttamente.
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
            // Messaggio di errore se non sono state fatte selezioni importanti
            alert('Please select at least one time slot and a payment method.');
        }
    });

});