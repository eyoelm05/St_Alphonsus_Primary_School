const login_form = document.getElementById('login_form');
const response_message = document.getElementById('response_message');

login_form.addEventListener('submit', async function (event) {
    event.preventDefault(); 
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    
    const data = { username, password };

    const options = {
        method: "POST",
        body: JSON.stringify(data),
        credentials: 'include'
    };

    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/login.php", options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        if(result.user_type === "parent"){
            setTimeout(() => {
                window.location.href = 'read_parent.html';
            }, 2000)
        }else if(result.employee_type === "A"){
            setTimeout(() => {
                window.location.href = 'to_be_approved.html';
            }, 2000); 
        }else{
            if(result.employee_type){
                setTimeout(() => {
                    window.location.href = 'read_teacher.html';
                }, 2000); 
            }else{
                setTimeout(() => {
                    window.location.href = 'wait_approval.html';
                }, 2000); 
            }
        }
    }else{
        response_message.className = "error"
    }
});
