const menu = document.getElementById("menu-toggle");

menu.addEventListener("click", () => {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
})