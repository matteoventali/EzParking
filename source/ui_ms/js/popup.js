let currentReservationId = null;

(function () {
    // DELETE MODAL
    const deleteBtns = document.querySelectorAll('.delete-booking-btn');
    const deleteModal = document.getElementById('deleteModal');

    let deleteBackdrop = null;
    let cancelBtn = null;

    if (deleteModal) {
        deleteBackdrop = deleteModal.querySelector('.modal-backdrop');
        cancelBtn = document.getElementById('cancelBtn');
    }

    function openDeleteModal() {
        if (!deleteModal) return;
        deleteModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        if (!deleteModal) return;
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

    let mapBackdrop = null;
    let closeMapBtn = null;

    if (mapModal) {
        mapBackdrop = mapModal.querySelector('.modal-backdrop');
        closeMapBtn = document.getElementById('closeMapBtn');
    }

    let mapInstance = null;

    function openMapModal(lat, lon) {
        if (!mapModal) return;

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
        if (!mapModal) return;

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

    // USER INFO MODAL
    const userInfoBtns = document.querySelectorAll('.user-info-btn');
    const userInfoModal = document.getElementById('userInfoModal');
    let userBackdrop = null;
    let closeUserInfoBtn = null;

    if (userInfoModal) {
        userBackdrop = userInfoModal.querySelector('.modal-backdrop');
        closeUserInfoBtn = document.getElementById('closeUserInfoBtn');
    }

    function openUserInfoModal(userId, reservationId) {
        if (!userInfoModal) return;

        userInfoModal.classList.add('active');
        document.body.style.overflow = 'hidden';

        // Fetching the info of the driver associated to the parking request
        xhr = new XMLHttpRequest();
        xhr.open("GET", "../php/fetch_user_info.php?id=" + encodeURIComponent(userId), true);
        xhr.onload = function() {
            if (this.status === 200) 
            {
                const response = JSON.parse(this.responseText);
                if (response.status === 200) 
                {
                    const data = response.body.user;
                    document.getElementById("infoName").textContent = data.name;
                    document.getElementById("infoSurname").textContent = data.surname;
                    document.getElementById("infoPhone").textContent = data.phone;
                    document.getElementById("infoScore").textContent = data.score;

                    const reviewLink = `../php/user_preview.php?user_id=${data.id}&reservation_id=${reservationId}`;
                    document.getElementById("infoReviews").href = reviewLink;
                } 
                else
                    alert("Error fetching user info: " + response.body.desc);
            }
            else
                alert("Error fetching user info: Server returned status " + this.status);
        };
        xhr.send();
    }

    function closeUserInfoModal() {
        if (!userInfoModal) return;

        userInfoModal.classList.remove('active');
        document.body.style.overflow = '';
    }

    userInfoBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const userId = btn.dataset.userid;
            const reservationId = btn.dataset.reservationid;
            openUserInfoModal(userId, reservationId);
        });
    });

    if (closeUserInfoBtn) closeUserInfoBtn.addEventListener('click', closeUserInfoModal);
    if (userBackdrop) userBackdrop.addEventListener('click', closeUserInfoModal);

    // ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (deleteModal && deleteModal.classList.contains('active')) closeDeleteModal();
            if (mapModal && mapModal.classList.contains('active')) closeMapModal();
        }
    });

    // Expose only if they exist
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