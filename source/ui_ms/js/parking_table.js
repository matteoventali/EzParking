
const parkingTableBody = document.getElementById("parkingTableBody");
const parkingSearchInput = document.getElementById("parkingSearchInput");
const parkingPrevBtn = document.getElementById("parkingPrevBtn");
const parkingNextBtn = document.getElementById("parkingNextBtn");
const parkingPageInfo = document.getElementById("parkingPageInfo");

// === Dati di esempio (simula fetch da DB) ===
let parkingData = [
  { id: 1, location: "Via Roma 45, Torino", capacity: 20, rep_treshold: 4, slot_price: 2.5, user_id: 2 },
  { id: 2, location: "Viale Marconi 10, Roma", capacity: 15, rep_treshold: 3, slot_price: 3.0, user_id: 3 },
  { id: 3, location: "Piazza Garibaldi, Napoli", capacity: 25, rep_treshold: 5, slot_price: 1.8, user_id: 4 },
  { id: 4, location: "Corso Italia 7, Milano", capacity: 10, rep_treshold: 2, slot_price: 2.0, user_id: 5 },
  { id: 5, location: "Via Po 88, Torino", capacity: 12, rep_treshold: 4, slot_price: 2.2, user_id: 2 },
];

// === Paginazione ===
let parkingCurrentPage = 1;
const parkingRowsPerPage = 3;

// === Funzione per mostrare parcheggi ===
function renderParkingTable(data) {
  parkingTableBody.innerHTML = "";
  data.forEach(spot => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${spot.id}</td>
      <td>${spot.location}</td>
      <td>${spot.capacity}</td>
      <td>${spot.rep_treshold}</td>
      <td>${spot.slot_price.toFixed(2)}</td>
      <td>${spot.user_id}</td>
    `;
    parkingTableBody.appendChild(row);
  });
}

// === Gestione paginazione ===
function paginateParking() {
  const start = (parkingCurrentPage - 1) * parkingRowsPerPage;
  const end = start + parkingRowsPerPage;
  const filteredData = parkingData.filter(p =>
    p.location.toLowerCase().includes(parkingSearchInput.value.toLowerCase())
  );
  const pageData = filteredData.slice(start, end);

  renderParkingTable(pageData);
  parkingPageInfo.textContent = `Page ${parkingCurrentPage} of ${Math.ceil(filteredData.length / parkingRowsPerPage)}`;

  parkingPrevBtn.disabled = parkingCurrentPage === 1;
  parkingNextBtn.disabled = end >= filteredData.length;
}

// === Eventi ===
parkingSearchInput.addEventListener("input", () => {
  parkingCurrentPage = 1;
  paginateParking();
});

parkingPrevBtn.addEventListener("click", () => {
  if (parkingCurrentPage > 1) {
    parkingCurrentPage--;
    paginateParking();
  }
});

parkingNextBtn.addEventListener("click", () => {
  const totalPages = Math.ceil(parkingData.length / parkingRowsPerPage);
  if (parkingCurrentPage < totalPages) {
    parkingCurrentPage++;
    paginateParking();
  }
});

// === Inizializzazione ===
paginateParking();
