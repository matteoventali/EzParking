
    // Calcolo dinamico del costo totale
    const startInput = document.getElementById('start_time');
    const endInput = document.getElementById('end_time');
    const totalCostEl = document.getElementById('totalCost');
    const pricePerHour = 2.5;

    function calculateCost() {
      const start = startInput.value;
      const end = endInput.value;
      if (!start || !end) {
        totalCostEl.textContent = "Costo totale: €0.00";
        return;
      }

      const startTime = new Date(`1970-01-01T${start}:00`);
      const endTime = new Date(`1970-01-01T${end}:00`);
      const diff = (endTime - startTime) / (1000 * 60 * 60); // ore

      if (diff <= 0) {
        totalCostEl.textContent = "Intervallo non valido";
        return;
      }

      const total = diff * pricePerHour;
      totalCostEl.textContent = `Costo totale: €${total.toFixed(2)}`;
    }

    startInput.addEventListener('change', calculateCost);
    endInput.addEventListener('change', calculateCost);


  
    document.getElementById('bookingForm').addEventListener('submit', function(event) {
        // Impedisce l'invio standard del form
        event.preventDefault();

        // Trova il metodo di pagamento selezionato
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

        if (selectedPayment) {
            let redirectUrl = '';
            const baseUrl = '../RES/payment/'; // Base URL fittizia per i reindirizzamenti

            // Imposta l'URL di reindirizzamento in base al metodo scelto
            switch (selectedPayment.value) {
                case 'applepay':
                    redirectUrl = baseUrl + 'applepay.php';
                    break;
                case 'googlepay':
                    redirectUrl = baseUrl + 'googlepay.php';
                    break;
                case 'paypal':
                    redirectUrl = baseUrl + 'paypal.php';
                    break;
                case 'creditcard':
                    redirectUrl = baseUrl + 'creditcard.php';
                    break;
                default:
                    // Se non c'è corrispondenza, usa l'azione originale del form
                    this.submit();
                    return;
            }

            // Aggiunge tutti i dati del form come parametri di query all'URL di reindirizzamento
            // (Questo è utile per passare i dati di prenotazione alla pagina di pagamento)
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();

            window.location.href = `${redirectUrl}?${params}`;

        } else {
            // Se nessun metodo di pagamento è selezionato (anche se "required" dovrebbe prevenire questo)
            alert('Please select a payment method.');
        }
    });
