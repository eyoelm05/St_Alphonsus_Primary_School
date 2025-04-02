const user_type = document.getElementById("user_type");
const submit_btn = document.getElementById("submit");
const register_form = document.getElementById("register_form");
const response_message = document.getElementById('response_message')

let employee_container = null;
let ta_container = null;

user_type.addEventListener("change", function () {
    if (user_type.value === "employee") {
        if (!employee_container) {
            add_employee_fields();
        }
    } else {
        remove_employee_fields();
        remove_TA_field();
    }
});

function add_employee_fields() {
    employee_container = document.createElement("div");
    employee_container.id = "employee_fields";

    const fields = [
        { label: "Background Check: ", type: "checkbox", id: "background_check", name: "background_check" },
        { label: "Date of Birth: ", type: "date", id: "date_of_birth", name: "date_of_birth" },
        { label: "Start Date: ", type: "date", id: "start_date", name: "start_date" }
    ];

    fields.forEach(field => {            
        const label = document.createElement("label");
        label.setAttribute("for", field.name);
        label.textContent = field.label;

        const input = document.createElement("input");
        input.type = field.type;
        input.id = field.id;
        input.name = field.name;
        input.required = true;

        if (field.placeholder) {
            input.placeholder = field.placeholder;
        }

        employee_container.appendChild(label);
        employee_container.appendChild(input);
        employee_container.appendChild(document.createElement("br"));
        employee_container.appendChild(document.createElement("br"));
    });

    const employee_type_label = document.createElement("label");
    employee_type_label.setAttribute("for", "employee_type");
    employee_type_label.textContent = "Employee Type: ";

    const employee_select = document.createElement("select");
    employee_select.id = "employee_type";
    employee_select.name = "employee_type";
    employee_select.required = true;

    const options = [
        {value: "T", text: "Teacher"},
        {value: "TA", text: "Teacher Assistant"}
    ];

    options.forEach(option => {
        const elem_option = document.createElement("option");
        elem_option.value = option.value;
        elem_option.text = option.text;
        employee_select.appendChild(elem_option);
    });
    employee_container.appendChild(employee_type_label);
    employee_container.appendChild(employee_select);
    employee_container.appendChild(document.createElement("br"));
    employee_container.appendChild(document.createElement("br"));

    register_form.insertBefore(employee_container, submit_btn);


    employee_select.addEventListener("change", function () {
        if (employee_select.value === "TA") {
            if(!ta_container){
                add_TA_field();
            }
        } else {
            remove_TA_field();
        }
    });
}

function add_TA_field() {
    ta_container = document.createElement("div");
    ta_container.id = "TA_fields"

    const class_name_label = document.createElement("label");
    class_name_label.setAttribute("for", "class_name");
    class_name_label.textContent = "Class Name: ";

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

    ta_container.appendChild(class_name_label);
    ta_container.appendChild(class_select);
    ta_container.appendChild(document.createElement("br"));
    ta_container.appendChild(document.createElement("br"));
    register_form.insertBefore(ta_container, submit_btn)
}

function remove_employee_fields() {
    if (employee_container) {
        employee_container.remove();
        employee_container = null;
    }
}

function remove_TA_field() {
    if (ta_container) {
        ta_container.remove();
        ta_container = null;
    }
}

register_form.addEventListener("submit", async (event)=>{
    event.preventDefault();
    const password = document.getElementById("password").value;
    const confirm_password = document.getElementById("confirm_password").value;

    if (password !== confirm_password) {
      alert("Passwords do not match.");
      return;
    }

    const username = document.getElementById("username").value;
    const first_name = document.getElementById("first_name").value;
    const middle_initial = document.getElementById("middle_initial").value;
    const last_name = document.getElementById("last_name").value;
    const email = document.getElementById("email").value;
    const phone_no = document.getElementById("phone_no").value;
    const address = document.getElementById("address").value;
    const sex = document.getElementById("sex").value;

    let data = {username, first_name, middle_initial, last_name, email, phone_no, address, sex, password};
    data.user_type = user_type.value;
    if(employee_container){
        const background_check = document.getElementById("background_check").checked;
        const date_of_birth = document.getElementById("date_of_birth").value;
        const start_date = document.getElementById("start_date").value;
        const employee_type = document.getElementById("employee_type").value;
        data.background_check = background_check;
        data.date_of_birth = date_of_birth;
        data.start_date = start_date;
        data.employee_type = employee_type;
        if(ta_container){
            const class_name = document.getElementById("class_name").value;
            data.class_name = class_name
        }
    }  

    const options = {
        method: "POST",
        body: JSON.stringify(data),
    };
    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/register.php", options);
    const result = await response.json();
    response_message.innerHTML = result.message;
})