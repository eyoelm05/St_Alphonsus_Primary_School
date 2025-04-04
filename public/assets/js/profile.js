const profile_form = document.getElementById("profile_form");
const response_message = document.getElementById('response_message')
const submit_btn = document.getElementById("submit");

const username = document.getElementById("username").value;
const first_name = document.getElementById("first_name").value;
const middle_initial = document.getElementById("middle_initial").value;
const last_name = document.getElementById("last_name").value;
const email = document.getElementById("email").value;
const phone_no = document.getElementById("phone_no").value;
const address = document.getElementById("address").value;
const sex = document.getElementById("sex").value;

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
            document.getElementById("username").value = result.username = result.user.username;
            document.getElementById("first_name").value = result.user.first_name;
            document.getElementById("middle_initial").value = result.user.last_name;
            document.getElementById("last_name").value = result.user.last_name;
            document.getElementById("email").value = result.user.email;
            document.getElementById("phone_no").value = result.user.phone_no;
            document.getElementById("address").value = result.user.address;
            document.getElementById("sex").value = result.user.sex
        }
}