let currentReservationId = null;

(function () {
    // DELETE MODAL
    const deleteBtns = document.querySelectorAll('.delete-booking-btn');
    const deleteModal = document.getElementById('deleteModal');
    const deleteBackdrop = deleteModal.querySelector('.modal-backdrop');
    const cancelBtn = document.getElementById('cancelBtn');

    function openDeleteModal() {
        deleteModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        deleteModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    deleteBtns.forEach(btn => {
        btn.addEventListener('click', openDeleteModal);
    });

    if (cancelBtn) cancelBtn.addEventListener('click', closeDeleteModal);
    if (deleteBackdrop) deleteBackdrop.addEventListener('click', closeDeleteModal);

    // MAP MODAL
    const mapBtns = document.querySelectorAll('.map-booking-btn');
    const mapModal = document.getElementById('mapModal');
    const mapBackdrop = mapModal.querySelector('.modal-backdrop');
    const closeMapBtn = document.getElementById('closeMapBtn');
    
    let mapInstance = null;

    function openMapModal(lat, lon) {
        mapModal.classList.add('active');
        document.body.style.overflow = 'hidden';

        if (mapInstance) {
            mapInstance.remove();
            mapInstance = null;
        }

        mapInstance = L.map('mapContainer').setView([lat, lon], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(mapInstance);

        L.marker([lat, lon]).addTo(mapInstance);
    }

    function closeMapModal() {
        mapModal.classList.remove('active');
        document.body.style.overflow = '';
        if (mapInstance) {
            mapInstance.remove();
            mapInstance = null;
        }
    }

    mapBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const lat = parseFloat(btn.dataset.lat);
            const lon = parseFloat(btn.dataset.lon);
            openMapModal(lat, lon);
        });
    });

    if (closeMapBtn) closeMapBtn.addEventListener('click', closeMapModal);
    if (mapBackdrop) mapBackdrop.addEventListener('click', closeMapModal);

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (deleteModal.classList.contains('active')) closeDeleteModal();
            if (mapModal.classList.contains('active')) closeMapModal();
        }
    });

    // Expose globally
    window.DeleteModal = { open: openDeleteModal, close: closeDeleteModal };
    window.MapModal = { open: openMapModal, close: closeMapModal };
})();

function setCurrentReservation(id)
{
    currentReservationId = id;
}

function performDelete() 
{
    // Checking if there is one reservation selected
    if (currentReservationId === null)
        return;
    
    // Performing an ajax request to delete the reservation
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/change_status_reservations.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() 
    {
        // Enconding JSON
        const response = JSON.parse(this.responseText);
        
        // Checking for errors
        if ( response.status == 200 )
            // Reload the page after deletion
            window.location.reload();
        else
            alert("Error cancelling the reservation: " + response.body.desc);
    };
    xhr.send("reservation_id=" + encodeURIComponent(currentReservationId) + "&new_status=cancelled");
}
