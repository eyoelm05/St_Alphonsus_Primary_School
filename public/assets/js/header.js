const menu = document.getElementById("menu-toggle");
const authButton = document.getElementById("auth_button")

check_logged_in()


function check_logged_in(){
    const cookie = document.cookie
    console.log(document.cookie)
    if(cookie){
        authButton.innerHTML = `<a href="#" id="log_out" class="sign-in">Log out</a>`;
        const log_out_btn = document.getElementById("log_out");
        log_out_btn.addEventListener("click", async () => {
            const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/logout.php", options);
            if(response.status === 200){
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 2000); 
            }
        })
    }else{
        authButton.innerHTML = `<a href="sign_in.html" class="sign-in">Sign In</a>`
    }
}

menu.addEventListener("click", () => {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
})