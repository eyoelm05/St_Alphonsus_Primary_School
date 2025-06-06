const container = document.getElementById("container");

async function read_parent() {
    const options = {
        method: "GET",
    };
    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/pupil/read_parent.php", options);
    const result = await response.json();

    if(response.status == 404){
        window.location.href = '404_page.html';
    }else if(response.status == 401){
        window.location.href = '401_page.html';
    }else if(response.status == 500){
        window.location.href = '500_page.html';
    }else{
        result.pupils.forEach(pupil => {
            const pupil_container = document.createElement('div');
            pupil_container.id = pupil.id;
            pupil_container.className = "pupil_container";

            pupil_container.innerHTML = `<h3>${pupil.name}</h3><h4>${pupil.current_class}</h4><h4>${pupil.date_of_birth}</h4>`
            container.appendChild(pupil_container);

            pupil_container.addEventListener("click", () => {
                const params = new URLSearchParams();
                params.append("id", pupil_container.id);
                location.href = "read_single.html?" + params.toString();
            })
        });
    }
}

read_parent();