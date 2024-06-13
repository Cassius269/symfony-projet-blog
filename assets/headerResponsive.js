// Selection des élements
let menuBurger = document.querySelector('.menuBurger');
let nav = document.querySelector("nav");
let iconeExitMenu = document.querySelector('.iconeExitMenu');
let headerMobile = document.querySelector(".headerMobile");

// Gestionnaires d'évenements de type clic sur l'icone du menu burger et le menu exit
menuBurger.addEventListener('click',()=>{
    nav.classList.toggle('mobile-menu');
});


iconeExitMenu.addEventListener('click',()=>{
    nav.classList.toggle('mobile-menu');
});

// Les médias queries
