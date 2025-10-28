const list = document.getElementById("requests-list");
const totalCount = document.getElementById("total-count");
const pendingCount = document.getElementById("pending-count");
const empty = document.getElementById("empty");

function updateStats() {
  const cards = [...list.querySelectorAll(".card")];
  totalCount.textContent = cards.length;
  const pend = cards.filter((c) =>
    c.querySelector(".status").classList.contains("pending")
  ).length;
  pendingCount.textContent = pend;
  empty.hidden = cards.length > 0;
}
list.addEventListener("click", (e) => {
  const btn = e.target.closest("button");
  if (!btn) return;
  const card = btn.closest(".card");
  const statusEl = card.querySelector(".status");
  const action = btn.dataset.action;
  if (action === "accept") {
    statusEl.textContent = "ACCEPTED";
    statusEl.classList.remove("pending");
    statusEl.classList.remove("rejected");
    statusEl.classList.add("accepted");
    card.style.opacity = "0.98";
  }
  if (action === "reject") {
    statusEl.textContent = "REJECTED";
    statusEl.classList.remove("pending");
    statusEl.classList.remove("accepted");
    statusEl.classList.add("rejected");
    card.style.opacity = "0.7";
  }
  updateStats();
});
document.getElementById("search").addEventListener("input", (e) => {
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
updateStats();
