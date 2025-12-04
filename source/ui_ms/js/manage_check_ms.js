// Templates for service status items
const TEMPLATE_ACTIVE = `
<div class="service-item service-active" data-status="active">
    <div class="service-header">
        <div class="service-status-indicator">
            <svg class="status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10" />
                <polyline points="8 12 11 15 16 9"></polyline>
            </svg>
        </div>
        <span class="service-name">%SERVICE_NAME%</span>
        <button class="expand-btn" aria-label="Expand details">
            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>
    <div class="service-details">
        <div class="details-content">
            <p><strong>Status:</strong> <span style="color: #00ff00;">%SERVICE_STATUS%</span></p>
            <p><strong>IP:</strong> %SERVICE_IP%</p>
            <p><strong>Port:</strong> %SERVICE_PORT%</p>
        </div>
    </div>
</div>
`;

const TEMPLATE_DOWN = `
<div class="service-item service-down" data-status="down">
    <div class="service-header">
        <div class="service-status-indicator">
            <svg class="status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <span class="service-name">%SERVICE_NAME%</span>
        <button class="expand-btn" aria-label="Expand details">
            <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>
    </div>
    <div class="service-details">
        <div class="details-content">
            <p><strong>Status:</strong> <span style="color: #f44336;">%SERVICE_STATUS%</span></p>
            <p><strong>IP:</strong> %SERVICE_IP%</p>
            <p><strong>Port:</strong> %SERVICE_PORT%</p>
            <p><strong>Error:</strong> %SERVICE_ERROR%</p>
        </div>
    </div>
</div>
`;

// Wrapper function
function updateDashboard()
{
    fetchMicroservicesStatus();
    fetchActiveUsers();
    fetchActiveReservations();
}

// Handling the toggle in the microservices card
document.addEventListener('DOMContentLoaded', function () 
{
    const container = document.querySelector('.services-list');

    container.addEventListener('click', function (e) {
        const button = e.target.closest('.expand-btn');
        if (!button) return;

        e.preventDefault();
        const serviceItem = button.closest('.service-item');
        serviceItem.classList.toggle('expanded');
    });
});

function fetchMicroservicesStatus()
{
    fetch('../php/check_status.php')
        .then(res => res.json())
        .then(data => {
            const container = document.querySelector('.services-list');
            container.innerHTML = ''; // Clean all

            let activeCount = 0;

            data.forEach(svc => {
                let card = (svc.status === 'active' ? TEMPLATE_ACTIVE : TEMPLATE_DOWN)
                    .replace(/%SERVICE_NAME%/g, svc.name)
                    .replace(/%SERVICE_STATUS%/g, svc.status)
                    .replace(/%SERVICE_IP%/g, svc.ip)
                    .replace(/%SERVICE_PORT%/g, svc.port)
                    .replace(/%SERVICE_ERROR%/g, svc.error ?? '—');

                if (svc.status === 'active') activeCount++;
                container.insertAdjacentHTML('beforeend', card);
            });

            // Updating the counter of microservices active
            document.querySelector('.microservices-card .service-count').textContent = activeCount;
        })
        .catch(err => console.error('Error fetching microservices status:', err));
}

// Handling the number of active users
function fetchActiveUsers()
{
    fetch('../php/check_active_users.php')
        .then(res => res.json())
        .then(data => {
            // Access the correct div
            const activeUsersDiv = document.getElementById('number_users');
            activeUsersDiv.innerHTML = ''; // Clean all
            activeUsersDiv.textContent = data;
        })
        .catch(err => console.error('Error fetching microservices status:', err));
}

// Handling the number of active reservations
function fetchActiveReservations()
{
    fetch('../php/check_active_reservations.php')
        .then(res => res.json())
        .then(data => {
            // Access the correct div
            const activeReservationsDiv = document.getElementById('number_reservations');
            activeReservationsDiv.innerHTML = ''; // Clean all
            activeReservationsDiv.textContent = data;
        })
        .catch(err => console.error('Error fetching microservices status:', err));
}

updateDashboard();

// Update the dashboard every 5 seconds
setInterval(updateDashboard, 5000);