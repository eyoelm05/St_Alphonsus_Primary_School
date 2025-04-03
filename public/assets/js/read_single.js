const container = document.getElementById("container");
const pupil_id = document.getElementById("id");
const name = document.getElementById("name");
const parents = document.getElementById("parents");
const date_of_birth = document.getElementById("date_of_birth");
const sex = document.getElementById("sex");
const address = document.getElementById("address");
const class_name = document.getElementById("class")
const class_teacher = document.getElementById("class_teacher");

async function read_single() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");

    const options = {
        method: "GET",
    };
    const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/pupil/read_single.php?id=${id}`, options);
    const result = await response.json();
    const pupil = result.pupil;


    pupil_id.textContent += `${pupil.id}`;
    name.textContent += pupil.name;
    parents.textContent += pupil.parents;
    date_of_birth.textContent += pupil.date_of_birth;
    sex.textContent += pupil.sex;
    class_name.textContent += pupil.class;
    class_teacher.textContent += pupil.teacher_name; 

    if(pupil.teacher_assistants){
        const teacher_assistants = createElement("h3");
        teacher_assistants.id = "teacher_assistant";
        teacher_assistants.textContent = `Teacher Assistants: ${pupil.teacher_assistants}`
        container.appendChild(teacher_assistants);
    }
    if(pupil.medicals){
        const medicals = document.createElement("h3");
        medicals.id = "medicals";
        medicals.textContent = `Medicals: ${pupil.medicals}`;
        container.appendChild(medicals)
    }
}

read_single();