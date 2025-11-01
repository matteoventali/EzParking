// --- Style variables ---
let circle_marker_color = '#ff0000';
let circle_marker_border_color = '#000000';

// --- Global variables ---
let map;
let userMarker = null;
let selectedMarker = null;

// --- Initialize the map ---
document.addEventListener('DOMContentLoaded', function () {
    map = L.map('map');
    map.setView([41.8719, 12.5674], 6); // Default: Italy

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Try to get user location
    if (navigator.geolocation) {
        const saved_lat = sessionStorage.getItem("user_latitude");
        const saved_lon = sessionStorage.getItem("user_longitude");

        if (saved_lat && saved_lon)
            showUserLocation(saved_lat, saved_lon);
        else 
        {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lon = pos.coords.longitude;

                // Save position for next session
                sessionStorage.setItem("user_latitude", lat);
                sessionStorage.setItem("user_longitude", lon);

                showUserLocation(lat, lon);
            }, () => {
                // Fallback to Italy
                showUserLocation(41.8719, 12.5674);
            });
        }
    } else {
        showUserLocation(41.8719, 12.5674);
    }

    // --- Enable click on map to select location ---
    map.on('click', function (e) 
    {
        const lat = e.latlng.lat.toFixed(6);
        const lon = e.latlng.lng.toFixed(6);

        // Remove previous selected marker
        if (selectedMarker) {
            map.removeLayer(selectedMarker);
        }

        // Add new marker
        selectedMarker = L.marker([lat, lon]).addTo(map);
        selectedMarker.bindPopup(`Selected point:<br>Lat: ${lat}<br>Lon: ${lon}`).openPopup();

        // Save coordinates in hidden form fields (if present)
        const latInput = document.getElementById('latitude');
        const lonInput = document.getElementById('longitude');
        if (latInput && lonInput) {
            latInput.value = lat;
            lonInput.value = lon;
        }

        const coordsDisplay = document.getElementById('coordsDisplay');
        if (coordsDisplay) {
            coordsDisplay.textContent = `Selected coordinates: ${lat}, ${lon}`;
        }

        console.log("Selected:", lat, lon);
    });
});

// --- Function to show the user’s location ---
function showUserLocation(lat, lon) 
{
    map.setView([lat, lon], 15);

    if (userMarker) {
        map.removeLayer(userMarker);
    }

    userMarker = L.circleMarker([lat, lon], {
        radius: 8,
        color: circle_marker_border_color,
        fillColor: circle_marker_color,
        fillOpacity: 0.6,
        weight: 2
    }).addTo(map);

    userMarker.bindPopup("You are here").openPopup();
}
