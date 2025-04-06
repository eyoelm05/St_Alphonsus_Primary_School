const register_form = document.getElementById("register_form");
const response_message = document.getElementById('response_message')

register_form.addEventListener("submit", async (event)=>{
    event.preventDefault();
    const password = document.getElementById("password").value;
    const confirm_password = document.getElementById("confirm_password").value;

    if (password !== confirm_password) {
      alert("Passwords do not match.");
      return;
    }

    const username = document.getElementById("username").value;
    const first_name = document.getElementById("first_name").value;
    const middle_initial = document.getElementById("middle_initial").value;
    const last_name = document.getElementById("last_name").value;
    const email = document.getElementById("email").value;
    const phone_no = document.getElementById("phone_no").value;
    const address = document.getElementById("address").value;
    const sex = document.getElementById("sex").value;
    const user_type = document.getElementById("user_type").value;

    const data = {username, first_name, middle_initial, last_name, email, phone_no, address, sex, user_type, password};

    const options = {
        method: "POST",
        body: JSON.stringify(data),
    };

    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/register.php", options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        setTimeout(() => {
            window.location.href = 'sign_in.html';
        }, 2000); 
    }
})