const userTableBody = document.getElementById("userTableBody");
const userSearchInput = document.getElementById("userSearchInput");
const userPrevBtn = document.getElementById("userPrevBtn");
const userNextBtn = document.getElementById("userNextBtn");
const userPageInfo = document.getElementById("userPageInfo");

let usersData = [
  { nome: "Federico", email: "federico@example.com", status: "active" },
  {  nome: "Luca",  email: "luca@example.com",status: "deactivated" },
  {  nome: "Giulia",  email: "giulia@example.com",active: "active" },
  {  nome: "Marta",  email: "marta@example.com", status: "active" },
  {  nome: "Alessio",  email: "alessio@example.com", status: "deactivated" },
    { nome: "Federico", email: "federico@example.com", status: "active" },
  {  nome: "Luca",  email: "luca@example.com",status: "deactivated" },
  {  nome: "Giulia",  email: "giulia@example.com",active: "active" },
  {  nome: "Marta",  email: "marta@example.com", status: "active" },
  {  nome: "Alessio",  email: "alessio@example.com", status: "deactivated" },
    { nome: "Federico", email: "federico@example.com", status: "active" },
  {  nome: "Luca",  email: "luca@example.com",status: "deactivated" },
  {  nome: "Giulia",  email: "giulia@example.com",active: "active" },
  {  nome: "Marta",  email: "marta@example.com", status: "active" },
  {  nome: "Alessio",  email: "alessio@example.com", status: "deactivated" },
    { nome: "Federico", email: "federico@example.com", status: "active" },
  {  nome: "Luca",  email: "luca@example.com",status: "deactivated" },
  {  nome: "Giulia",  email: "giulia@example.com",active: "active" },
  {  nome: "Marta",  email: "marta@example.com", status: "active" },
  {  nome: "Alessio",  email: "alessio@example.com", status: "deactivated" },
];

let userCurrentPage = 1;
const userRowsPerPage = 10;

function renderUsersTable(data) {
  userTableBody.innerHTML = "";

  data.forEach(user => {
    const row = document.createElement("tr");

    // Scegli l'icona in base allo stato
    const statusIcon = user.status === "active"
      ? "../images/active.svg"
      : "../images/deactivated.svg";

    row.innerHTML = `
      
      <td>${user.email}</td>
      <td>
        <img src="${statusIcon}" alt="${user.status}" style="width:20px">
      </td>
    `;

    // Clic sulla riga → vai ai dettagli dell’utente
    row.addEventListener("click", () => {
      window.location.href = `user_details.html?id=${user.id}`;
    });

    row.style.cursor = "pointer";
    userTableBody.appendChild(row);
  });
}

function paginateUsers() {
  const start = (userCurrentPage - 1) * userRowsPerPage;
  const end = start + userRowsPerPage;
  const filteredData = usersData.filter(u =>

    u.email.toLowerCase().includes(userSearchInput.value.toLowerCase())
  );
  const pageData = filteredData.slice(start, end);

  renderUsersTable(pageData);
  userPageInfo.textContent = `Page ${userCurrentPage} of ${Math.ceil(filteredData.length / userRowsPerPage)}`;

  userPrevBtn.disabled = userCurrentPage === 1;
  userNextBtn.disabled = end >= filteredData.length;
}

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

paginateUsers();
