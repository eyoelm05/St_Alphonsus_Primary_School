const profile_form = document.getElementById("profile_form");
const response_message = document.getElementById('response_message');
const delete_btn = document.getElementById("delete");

fetch_profile();

async function fetch_profile() {
        const options = {
            method: "GET",
        };
        const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/profile.php", options);
        const result = await response.json();

        if(response.status == 404){
            window.location.href = '404_page.html';
        }else if(response.status == 401){
            window.location.href = '401_page.html';
        }else if(response.status == 500){
            window.location.href = '500_page.html';
        }else{
            document.getElementById("username").value = result.user.username;
            document.getElementById("first_name").value = result.user.first_name;
            document.getElementById("middle_initial").value = result.user.middle_initial;
            document.getElementById("last_name").value = result.user.last_name;
            document.getElementById("email").value = result.user.email;
            document.getElementById("phone_no").value = result.user.phone_no;
            document.getElementById("address").value = result.user.address;
            document.getElementById("sex").value = result.user.sex
        }
}

profile_form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const username = document.getElementById("username").value;
    const first_name = document.getElementById("first_name").value;
    const middle_initial = document.getElementById("middle_initial").value;
    const last_name = document.getElementById("last_name").value;
    const email = document.getElementById("email").value;
    const phone_no = document.getElementById("phone_no").value;
    const address = document.getElementById("address").value;
    const sex = document.getElementById("sex").value;

    const data = {username, first_name, middle_initial, last_name, email, phone_no, address, sex};

    const options = {
        method: "PUT",
        body: JSON.stringify(data),
    };

    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/update.php", options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        if(result.user_type === "parent"){
            setTimeout(() => {
                window.location.href = 'read_parent.html';
            }, 2000)
        }else{
            setTimeout(() => {
                window.location.href = 'read_teacher.html';
            }, 2000); 
        }
    }
})


delete_btn.addEventListener("click", async () => {
    const confirmed = confirm("Are you sure you want to delete your account? This action cannot be undone.");

    if (confirmed) {
        const options = {
            method: "DELETE",
        };

        const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/delete.php", options);
        const result = await response.json();

        response_message.innerHTML = result.message;

        if(response.status == 200){
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 2000);
        } else if (response.status == 401) {
            window.location.href = '401_page.html';
        } else if (response.status == 500) {
            window.location.href = '500_page.html';
        }
    }
});
