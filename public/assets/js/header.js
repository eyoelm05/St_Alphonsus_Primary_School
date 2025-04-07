const menu = document.getElementById("menu_toggle");
const nav_links = document.getElementById("nav_links")

check_logged_in()


async function check_logged_in(){
    const options = {
        method: "GET",
    };
    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/check_user.php", options);
    const result = await response.json();

    const auth_button = document.createElement("div");
    auth_button.id = "auth_button";
    auth_button.className = "auth-buttons";
    let links;
    if(response.status === 200){
        if(result.user_type === "parent"){
            links = `
            <a href="index.html">Home</a>
            <a href="read_parent.html">Your children</a>
            <a href="add_pupil.html">Add pupil</a>
            <a href="profile.html">Profile</a>`
        }else if(result.employee_type === "A"){
            links = `
            <a href="index.html">Home</a>
            <a href="to_be_approved.html">Approve teacher</a>
            <a href="all_classes.html">Classes</a>
            <a href="profile.html">Profile</a>
            `
        }else if(result.employee_type === "T" && result.employee_type === "TA"){
            links = `
            <a href="index.html">Home</a>
            <a href="read_teacher.html">Your class</a>
            <a href="profile.html">Profile</a>
            `
        }else{
            links = `
            <a href="index.html">Home</a>
            <a href="wait_approval.html">Waiting page</a>
            <a href="profile.html">Profile</a>
            `
        }

        nav_links.innerHTML = links
        auth_button.innerHTML = `<a href="#" id="log_out" class="sign-in">Log out</a>`;
        nav_links.appendChild(auth_button)
        const log_out_btn = document.getElementById("log_out");
        const options1 = {
            method: "POST",
            credentials: 'include'
        };

        log_out_btn.addEventListener("click", async () => {
            const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/logout.php", options1);
            if(response.status === 200){
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 2000); 
            }
        })
    }else{
        links = `<a href="index.html">Home</a>`;
        nav_links.innerHTML = links;
        auth_button.innerHTML = `<a href="sign_in.html" class="sign-in">Sign In</a>`
        nav_links.appendChild(auth_button)
    }
}

menu.addEventListener("click", () => {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
})