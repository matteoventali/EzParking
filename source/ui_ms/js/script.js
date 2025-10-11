const form = document.getElementById("login-form");
const errorMessage = document.getElementById("error-message");

form.addEventListener("submit", function(event) {
    event.preventDefault(); // evita refresh pagina

    const username = form.username.value.trim();
    const password = form.password.value.trim();

    // Controllo base (puoi sostituire con chiamata API)
    if(username === "admin" && password === "1234") {
        alert("Login riuscito!");
        // qui puoi reindirizzare: window.location.href = "home.html";
    } else {
        errorMessage.textContent = "Username o password errati!";
    }
});