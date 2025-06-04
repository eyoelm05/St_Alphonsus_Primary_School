const container = document.getElementById("container");

async function read_teacher() {
    const params = new URLSearchParams(window.location.search);
    const class_name = params.get("class_name");
    let url;
    const options = {
        method: "GET",
    };
    if(class_name){
        url = `http://localhost/St_Alphonsus_Primary_School/api/pupil/read_teacher.php?class_name=${class_name}`
    }else{
        url = `http://localhost/St_Alphonsus_Primary_School/api/pupil/read_teacher.php`
    }
    const response = await fetch(url, options);
    const result = await response.json();

    if(response.status == 404){
        window.location.href = '404_page.html';
    }else if(response.status == 401){
        window.location.href = '401_page.html';
    }else if(response.status == 500){
        window.location.href = '500_page.html';
    }else if(response.status == 400){
        window.location.href = 'wait_approval.html';
    }else{
        const class_name = document.createElement("h1");
        class_name.textContent = `Pupils in ${result.class}`;

        container.appendChild(class_name);
        result.pupils.forEach(pupil => {
            const pupil_container = document.createElement('div');
            pupil_container.id = pupil.id;
            pupil_container.className = "pupil_container";

            pupil_container.innerHTML = `<p class="pupil_id">${pupil.id}</p><p class="pupil_name">${pupil.name}</p>`
            container.appendChild(pupil_container);

            pupil_container.addEventListener("click", () => {
                const params = new URLSearchParams();
                params.append("id", pupil_container.id);
                location.href = "read_single.html?" + params.toString();
            })
        })
    }
}

read_teacher();