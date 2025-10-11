
const accountBtn = document.getElementById('account-btn');
const dropdownMenu = document.getElementById('dropdown-menu');

// Toggle del menu a tendina
accountBtn.addEventListener('click', () => {
console.log('Account button clicked');  
dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
});

// Chiudi il menu cliccando fuori
window.addEventListener('click', (e) => {
if (!accountBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
    dropdownMenu.style.display = 'none';
}
});

