const list = document.getElementById("requests-list");
const totalCount = document.getElementById("total-count");
const pendingCount = document.getElementById("pending-count");
const empty = document.getElementById("empty");

function updateStats() 
{
    const cards = [...list.querySelectorAll(".card")];
    totalCount.textContent = cards.length;
    const pend = cards.filter((c) =>
    c.querySelector(".status").classList.contains("pending")
    ).length;
    pendingCount.textContent = pend;
}

document.getElementById("search").addEventListener("input", (e) => 
{
    const q = e.target.value.trim().toLowerCase();
    const cards = [...list.querySelectorAll(".card")];
    cards.forEach((c) => {
    const text = (
        c.querySelector(".parking").textContent +
        " " +
        c.querySelector(".address").textContent
    ).toLowerCase();
    c.style.display = text.includes(q) ? "flex" : "none";
    });
    updateStats();
});