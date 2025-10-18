const menu = document.querySelector('#mobile-menu');
const menuLinks = document.querySelector('.navbar__menu');
const toggleBarLinks = document.querySelectorAll('#link-togle-bar');

// Toggle apertura/chiusura menu mobile
menu.addEventListener('click', function() {
    menu.classList.toggle('is-active');
    menuLinks.classList.toggle('active');

    toggleBarLinks.forEach(link => {
        if (link.style.display === 'none' || link.style.display === '') {
            link.style.display = 'block';
        } else {
            link.style.display = 'none';
        }
    });
});

// 🔹 Quando la finestra viene ridimensionata
window.addEventListener('resize', function() {
    // Se torna sopra la soglia mobile (es. 960px)
    if (window.innerWidth > 960) {
        toggleBarLinks.forEach(link => {
            link.style.display = 'none'; // nasconde i link
        });
        menu.classList.remove('is-active');
        menuLinks.classList.remove('active');
    }
});
