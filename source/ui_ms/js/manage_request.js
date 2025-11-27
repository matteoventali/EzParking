const list = document.getElementById("requests-list");
const totalCount = document.getElementById("total-count");

function updateStats() {
    const cards = [...list.querySelectorAll(".card")];
    totalCount.textContent = cards.length;
}

document.getElementById("search").addEventListener("input", (e) => {
    const q = e.target.value.trim().toLowerCase();
    const cards = [...list.querySelectorAll(".card")];

    cards.forEach((c) => {
        const parkingName = c.querySelector(".parking").textContent.toLowerCase();
        const requesterName = c.querySelector(".requester-name").textContent.toLowerCase();
        const matches = parkingName.includes(q) || requesterName.includes(q);
        c.style.display = matches ? "flex" : "none";
    });

    updateStats();
});

function manageRequest(requestId, action) 
{
    // Fetching the info of the driver associated to the parking request
    xhr = new XMLHttpRequest();
    xhr.open("POST", "../php/manage_request.php", true);
    
    // Enconding the paylaod to send
    const params = new URLSearchParams();
    params.append('request_id', requestId);
    params.append('action', action);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() 
    {
        if (this.status === 200) 
        {
            const response = JSON.parse(this.responseText);
            if (response.status === 200) 
            {
                // Successfully managed the request, remove the card from the list
                removeCard(requestId, action);
            } 
            else
                alert("Error managing request: " + response.body.desc);
        }
        else
            alert("Error managing request: Server returned status " + this.status);
    };
    xhr.send(params.toString());
}

function removeCard(requestId, action) 
{
    const card = document.querySelector(`.card[data-id="${requestId}"]`);
    if (!card) return;

    if (action === "accept")
        card.classList.add("card-slide-right");
    else if (action === "reject")
        card.classList.add("card-slide-left");
    
    setTimeout(() => {
        card.remove();
        updateStats();
        location.href = '../php/manage_requests.php';
        location.reload();
    }, 650);
}
