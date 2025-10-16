
const userTableBody = document.getElementById("userTableBody");
const userSearchInput = document.getElementById("userSearchInput");
const userPrevBtn = document.getElementById("userPrevBtn");
const userNextBtn = document.getElementById("userNextBtn");
const userPageInfo = document.getElementById("userPageInfo");

// === Dati di esempio (simula fetch da DB) ===
let usersData = [
  { id: 1, nome: "Federico", cognome: "De Lullo", email: "federico@example.com", telefono: "+39 333 1234567", ruolo: "Admin" },
  { id: 2, nome: "Luca", cognome: "Bianchi", email: "luca@example.com", telefono: "+39 333 9876543", ruolo: "User" },
  { id: 3, nome: "Giulia", cognome: "Rossi", email: "giulia@example.com", telefono: "+39 331 5552233", ruolo: "User" },
  { id: 4, nome: "Marta", cognome: "Verdi", email: "marta@example.com", telefono: "+39 345 1122334", ruolo: "User" },
  { id: 5, nome: "Alessio", cognome: "Neri", email: "alessio@example.com", telefono: "+39 350 7788990", ruolo: "User" },
];

// === Paginazione ===
let userCurrentPage = 1;
const userRowsPerPage = 3;

// === Funzione per mostrare utenti ===
function renderUsersTable(data) {
  userTableBody.innerHTML = "";
  data.forEach(user => {
    const row = document.createElement("tr");
    row.innerHTML = `
      <td>${user.id}</td>
      <td>${user.nome}</td>
      <td>${user.cognome}</td>
      <td>${user.email}</td>
      <td>${user.telefono}</td>
      <td>${user.ruolo}</td>
    `;
    userTableBody.appendChild(row);
  });
}

// === Gestione paginazione ===
function paginateUsers() {
  const start = (userCurrentPage - 1) * userRowsPerPage;
  const end = start + userRowsPerPage;
  const filteredData = usersData.filter(u =>
    u.nome.toLowerCase().includes(userSearchInput.value.toLowerCase()) ||
    u.cognome.toLowerCase().includes(userSearchInput.value.toLowerCase()) ||
    u.email.toLowerCase().includes(userSearchInput.value.toLowerCase())
  );
  const pageData = filteredData.slice(start, end);

  renderUsersTable(pageData);
  userPageInfo.textContent = `Page ${userCurrentPage} of ${Math.ceil(filteredData.length / userRowsPerPage)}`;

  userPrevBtn.disabled = userCurrentPage === 1;
  userNextBtn.disabled = end >= filteredData.length;
}

// === Eventi ===
userSearchInput.addEventListener("input", () => {
  userCurrentPage = 1;
  paginateUsers();
});

userPrevBtn.addEventListener("click", () => {
  if (userCurrentPage > 1) {
    userCurrentPage--;
    paginateUsers();
  }
});

userNextBtn.addEventListener("click", () => {
  const totalPages = Math.ceil(usersData.length / userRowsPerPage);
  if (userCurrentPage < totalPages) {
    userCurrentPage++;
    paginateUsers();
  }
});

// === Inizializzazione ===
paginateUsers();
