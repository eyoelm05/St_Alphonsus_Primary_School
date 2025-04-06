const container = document.getElementById("container");

async function to_be_approved() {
    const options = {
        method: "GET",
    };
    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/admins/to_be_approved.php", options);
    const result = await response.json();
    console.lgo
    if(response.status == 404){
        window.location.href = '404_page.html';
    }else if(response.status == 401){
        console.log(result)
        //window.location.href = '401_page.html';
    }else if(response.status == 500){
        window.location.href = '500_page.html';
    }else{
        result.employees.forEach(employee => {
            const employee_container = document.createElement('div');
            employee_container.username = employee.id;
            employee_container.className = "employee_container";

            employee_container.innerHTML = `<hr><p>${employee.username}</p><p>${employee.name}</p><hr>`
            container.appendChild(employee_container);

            employee_container.addEventListener("click", () => {
                const params = new URLSearchParams();
                params.append("id", employee_container.id);
                location.href = "read_single.html?" + params.toString();
            })
        });
    }
}

to_be_approved();