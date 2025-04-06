const container = document.getElementById("container");

async function read_classes() {
    const options = {
        method: "GET",
    };
    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/admins/all_classes.php", options);
    const result = await response.json();

    if(response.status == 404){
        window.location.href = '404_page.html';
    }else if(response.status == 401){
        window.location.href = '401_page.html';
    }else if(response.status == 500){
        window.location.href = '500_page.html';
    }else{
        result.classes.forEach(elem => {
            const class_container = document.createElement('div');
            class_container.id = elem.class_name;
            class_container.className = "class_container";

            class_container.innerHTML = `
            <hr><h3>${elem.class_name}</h3>
            <h4>${elem.class_capacity}</h4>
            <h4>${elem.teacher_name}</h4>
            <a href="update_class.html?class_name=${elem.class_name}">Update Class</a>
            `
            class_container.addEventListener("click", () => {
                const params = new URLSearchParams();
                params.append("class_name", elem.class_name);
                location.href = "read_teacher.html?" + params.toString();
            })
            container.appendChild(class_container);
        });
    }
}

read_classes();