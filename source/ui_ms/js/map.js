// Style variables
let circle_marker_color = '#ff0000';
let circle_marker_border_color = '#000000';

// Global array to keep track of added markers
let markers = [];

// Initialize the map centered on user's location (if available)
document.addEventListener("DOMContentLoaded", () => 
{
    const map = L.map('map').setView([41.9028, 12.4964], 13); // Default localization is Rome

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Try to get user location
    if (navigator.geolocation) 
    {
        navigator.geolocation.getCurrentPosition(pos => { // Success location of the user
            const lat = pos.coords.latitude;
            const lon = pos.coords.longitude;
            map.setView([lat, lon], 15);

            // Add a circular marker for the user position
            const userCircle = L.circleMarker([lat, lon], {
                radius: 8,           
                color: circle_marker_border_color,
                fillColor: circle_marker_color,
                fillOpacity: 0.6,    
                weight: 2
            }).addTo(map);
            userCircle.bindPopup("You are here").openPopup();
        }, 
        () => { // Fail
            map.setView([41.8719, 12.5674], 6); // Default localization is Italy
        });
    }
});

function addParkingMarker(map, park) 
{
    // Creation of the marker
    const marker = L.marker([park.lat, park.lon]).addTo(map);

    // Attach id for future search
    marker.parkingId = park.id;
    marker.parkData = park; // optional: store full object if needed

    // Bind a simple popup
    marker.bindPopup(`<b>${park.name}</b><br>Available: ${park.available}`);

    // When we click on the mark start the search
    marker.on('click', () => {
        fetchParkingDetailsAndShow(marker.parkingId);
    });

    markers.push(marker); // Keep track of the marker
    return marker;
}

function removeMarker(map, lat, lon) 
{
    // Search for markers in that position
    for (let i = 0; i < markers.length; i++) 
    {
        const m = markers[i];
        const pos = m.getLatLng();

        if (Math.abs(pos.lat - lat) < 0.0001 && Math.abs(pos.lng - lon) < 0.0001) {
            map.removeLayer(m);
            markers.splice(i, 1);
            break;
        }
    }
}

function fetchParkingDetailsAndShow(parkingId) 
{
    /* To be filled */
}
