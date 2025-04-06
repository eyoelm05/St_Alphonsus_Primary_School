const approve_form = document.getElementById("approve_form");
const response_message = document.getElementById('response_message');
const params = new URLSearchParams(window.location.search);
const username = params.get("username");

approve_form.addEventListener("submit", async (event)=>{
    event.preventDefault();
    const background_check = document.getElementById("background_check").value;
    const date_of_birth = document.getElementById("date_of_birth").value;
    const employee_type = document.getElementById("employee_type").value;
    const start_date = document.getElementById("start_date").value;
    const class_name = document.getElementById("class_name").value;

    const data = {background_check, date_of_birth, employee_type, start_date, class_name};

    const options = {
        method: "POST",
        body: JSON.stringify(data),
    };

    const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/admins/approve.php?username=${username}`, options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        response_message.className = "success"
        setTimeout(() => {
            window.location.href = 'to_be_approved.html';
        }, 2000); 
    }else{
        response_message.className = "error"
    }
})