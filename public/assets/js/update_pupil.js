const update_form = document.getElementById("update_form");
const response_message = document.getElementById('response_message');
const no_medicals = document.getElementById("no_medicals");
const submit_btn = document.getElementById("submit");
const params = new URLSearchParams(window.location.search);
const id = params.get("id");

const medical_container = document.createElement("div");
medical_container.id = "medicals";
fetch_pupil();

async function fetch_pupil() {
    const options = {
        method: "GET",
    };
    const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/pupil/read_single.php?id=${id}`, options);
    const result = await response.json();

    if(response.status == 404){
        window.location.href = '404_page.html';
    }else if(response.status == 401){
        window.location.href = '401_page.html';
    }else if(response.status == 500){
        window.location.href = '500_page.html';
    }else{
        document.getElementById("id").value = result.pupil.id;
        document.getElementById("first_name").value = result.pupil.first_name;
        document.getElementById("middle_initial").value = result.pupil.middle_initial;
        document.getElementById("last_name").value = result.pupil.last_name;
        document.getElementById("address").value = result.pupil.address;
        document.getElementById("date_of_birth").value = result.pupil.date_of_birth;
        document.getElementById("sex").value = result.pupil.sex;
        document.getElementById("class_name").value = result.pupil.class;
        document.getElementById("no_medicals").value = result.pupil.medicals.split(",").length;

        result.pupil.medicals.split(",").forEach(elem => {
            const medical = document.createElement("input");
            medical.type = "text";
            medical.className = "medical";
            medical.value = elem;
            medical.required = true;
            medical_container.appendChild(medical);
            medical_container.appendChild(document.createElement("br"));
            medical_container.appendChild(document.createElement("br"));
        });
        update_form.insertBefore(medical_container, submit_btn)
    }
}

no_medicals.addEventListener("change", () => {
    const current_values = medical_container.querySelectorAll(".medical");

    if (no_medicals.value > current_values.length) {
        for (let i = current_values.length; i < no_medicals.value; i++) {
            const medical = document.createElement("input");
            medical.type = "text";
            medical.className = "medical";
            medical.required = true;
            medical_container.appendChild(medical);
            medical_container.appendChild(document.createElement("br"));
            medical_container.appendChild(document.createElement("br"));
        }
    }else if (no_medicals.value < current_values.length) {
        for (let i = current_values.length - 1; i >= no_medicals.value; i--) {
            current_values[i].nextSibling.remove();
            current_values[i].nextSibling.remove();
            current_values[i].remove();
        }
    }

    if (!update_form.contains(medical_container)) {
        update_form.insertBefore(medical_container, submit_btn);
    }
});


update_form.addEventListener("submit", async (event)=>{
    event.preventDefault();
    const first_name = document.getElementById("first_name").value;
    const middle_initial = document.getElementById("middle_initial").value;
    const last_name = document.getElementById("last_name").value;
    const address = document.getElementById("address").value;
    const sex = document.getElementById("sex").value;
    const date_of_birth = document.getElementById("date_of_birth").value;
    const class_name = document.getElementById("class_name").value;

    const medicals = []
    const medical = document.querySelectorAll(".medical");

    medical.forEach((element) => {
        medicals.push(element.value);
    })

    const data = {first_name, middle_initial, last_name, address, sex, date_of_birth, class_name, medicals};

    const options = {
        method: "PUT",
        body: JSON.stringify(data),
    };

    const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/pupil/update.php?id=${id}`, options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        response_message.className = "success"
        setTimeout(() => {
            window.location.href = 'read_parent.html';
        }, 2000)
    }else{
        response_message.className = "error"
    }
})