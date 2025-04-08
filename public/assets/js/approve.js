const approve_form = document.getElementById("approve_form");
const response_message = document.getElementById('response_message');
const params = new URLSearchParams(window.location.search);
const username = params.get("username");
const employee_type = document.getElementById("employee_type")
const submit_btn = document.getElementById("submit")

let class_container = null
employee_type.addEventListener("change", () => {
    if (class_container) {
        class_container.remove();
        class_container = null;
    }

    if(employee_type.value === "TA"){
        class_container = document.createElement("div");
        class_container.id = "TA_fields"

        const class_label = document.createElement("label");
        class_label.setAttribute("for", "class_name");
        class_label.textContent = "Class: ";

        const class_select = document.createElement("select");
        class_select.id = "class_name";
        class_select.name = "class_name";
        class_select.required = true;

        const options = [
            {value: "Year 0", text: "Reception Year"},
            {value: "Year 1", text: "Year 1"},
            {value: "Year 2", text: "Year 2"},
            {value: "Year 3", text: "Year 3"},
            {value: "Year 4", text: "Year 4"},
            {value: "Year 5", text: "Year 5"},
            {value: "Year 6", text: "Year 6"}
        ];
        
        options.forEach(option => {
            const elem_option = document.createElement("option");
            elem_option.value = option.value;
            elem_option.text = option.text;
            class_select.appendChild(elem_option);
        });

        class_container.appendChild(class_label);
        class_container.appendChild(class_select);
        approve_form.insertBefore(class_container, submit_btn);
    }
})


approve_form.addEventListener("submit", async (event)=>{
    event.preventDefault();
    const background_check = document.getElementById("background_check").value;
    const date_of_birth = document.getElementById("date_of_birth").value;
    const employee_type = document.getElementById("employee_type").value;
    const start_date = document.getElementById("start_date").value

    let data;
    if(employee_type.value === "TA"){
        const class_name = document.getElementById("class_name").value;
        data = {background_check, date_of_birth, employee_type, start_date, class_name};
    }else{
        data = {background_check, date_of_birth, employee_type, start_date};
    }

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
            window.location.href = 'all_classes.html';
        }, 3000); 
    }else{
        response_message.className = "error"
    }
})