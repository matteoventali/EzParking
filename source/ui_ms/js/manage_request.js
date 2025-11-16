const list = document.getElementById("requests-list");
const totalCount = document.getElementById("total-count");
// Remove pendingCount if non usato
// const pendingCount = document.getElementById("pending-count");

function updateStats() {
    const cards = [...list.querySelectorAll(".card")];
    totalCount.textContent = cards.length;
    /*
    const pend = cards.filter((c) =>
        c.querySelector(".status").classList.contains("pending")
    ).length;
    pendingCount.textContent = pend;
    */
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
