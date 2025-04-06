const update_class_form = document.getElementById("update_class_form");
const response_message = document.getElementById('response_message');
const submit_btn = document.getElementById("submit");

const params = new URLSearchParams(window.location.search);
const class_name = params.get("class_name");
fetch_class();
async function fetch_class() {
        const options = {
            method: "GET",
        };
        const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/admins/single_class.php?class_name=${class_name}`, options);
        const result = await response.json();

        if(response.status == 404){
            window.location.href = '404_page.html';
        }else if(response.status == 401){
            window.location.href = '401_page.html';
        }else if(response.status == 500){
            window.location.href = '500_page.html';
        }else{
            document.getElementById("class_capacity").value = result.class.class_capacity;

            const teacher_label = document.createElement("label");
            teacher_label.setAttribute("for", "teacher");
            teacher_label.textContent = "Class Teacher: ";
            
            const teacher_select = document.createElement("select");
            teacher_select.id = "teacher";
            teacher_select.name = "teacher";
            teacher_select.required = true;
        
            result.teachers.forEach(teacher => {
                const elem_option = document.createElement("option");
                elem_option.value = teacher.username;
                elem_option.text = teacher.teacher_name;
                teacher_select.appendChild(elem_option);

                if(teacher.teacher_name === result.class.teacher_name){
                    elem_option.selected = true;
                }
            });

            update_class_form.insertBefore(teacher_label, submit_btn);
            update_class_form.insertBefore(teacher_select, submit_btn);
            update_class_form.insertBefore(document.createElement("br"), submit_btn);
            update_class_form.insertBefore(document.createElement("br"), submit_btn);
        }
}

update_class_form.addEventListener("submit", async (event) => {
    event.preventDefault();
    const class_capacity = document.getElementById("class_capacity").value;
    const teacher = document.getElementById("teacher").value;

    const data = {class_capacity, teacher};

    const options = {
        method: "PUT",
        body: JSON.stringify(data),
    };

    const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/admins/update.php?class_name=${class_name}`, options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        setTimeout(() => {
            window.location.href = 'all_classes.html';
        }, 2000)
    }
})
