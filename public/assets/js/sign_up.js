const user_type = document.getElementById("user_type");
const submit_btn = document.getElementById("submit");
const register_form = document.getElementById("register_form");
const employee_type = document.getElementById("employee_type");

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
