const add_form = document.getElementById("add_form");
const exists = document.getElementById("exists");
const submit_btn = document.getElementById("submit");
const response_message = document.getElementById("response_message")

const add_container = document.createElement("div");
add_container.id = "add_container";

exists.addEventListener("change", () => {
    add_container.innerHTML = "";
    if(exists.value === "true"){
        existing_student();
    }else{
        new_student()
    }

    add_form.insertBefore(add_container, submit_btn)
} )

function existing_student(){
        const id_label = document.createElement("label");
        id_label.setAttribute("for", "id");
        id_label.textContent = "What is your student's Id? \n (Retrieve from other guardian or teacher) ";

        const id_input = document.createElement("input");
        id_input.type = "number";
        id_input.id = "id";
        id_input.name = "id";
        id_input.min = 100;
        id_input.required = true;

        const relationship_label = document.createElement("label");
        relationship_label.setAttribute("for", "relationship");
        relationship_label.textContent = "What is your relationship with the student? ";

        const relationship_select = document.createElement("select");
        relationship_select.id = "relationship";
        relationship_select.name = "relationship";
        relationship_select.required = true;

        const options = [
            {value: "Mother", text: "Mother"},
            {value: "Father", text: "Father"},
            {value: "Legal Guardian", text: "Legal Guardian"}
        ];

        options.forEach(option => {
            const elem_option = document.createElement("option");
            elem_option.value = option.value;
            elem_option.text = option.text;
            relationship_select.appendChild(elem_option);
        });

        add_container.appendChild(id_label);
        add_container.appendChild(id_input);
        add_container.appendChild(document.createElement("br"));
        add_container.appendChild(document.createElement("br"));
        add_container.appendChild(relationship_label);
        add_container.appendChild(relationship_select);
        add_container.appendChild(document.createElement("br"));
        add_container.appendChild(document.createElement("br"));
}

function new_student(){
    const fields = [
        { label: "First Name: ", type: "text", id: "first_name", name: "first_name", length: 50 },
        { label: "Middle Initial: ", type: "text", id: "middle_initial", name: "middle_initial", length: 1 },
        { label: "Last Name: ", type: "text", id: "last_name", name: "last_name", length: 50},
        { label: "Sex: ", type: "text", id: "sex", name: "sex", length: 1 },
        { label: "Address: ", type: "text", id: "address", name: "address", length: 255},
        { label: "Date of Birth: ", type: "date", id: "date_of_birth", name: "date_of_birth" },
    ]

    fields.forEach(field => {            
        const label = document.createElement("label");
        label.setAttribute("for", field.name);
        label.textContent = field.label;

        const input = document.createElement("input");
        input.type = field.type;
        input.id = field.id;
        input.name = field.name;
        input.required = true;
        input.maxLength = field.length;

        add_container.appendChild(label);
        add_container.appendChild(input);
        add_container.appendChild(document.createElement("br"));
        add_container.appendChild(document.createElement("br"));
    });

    const relationship_label = document.createElement("label");
    relationship_label.setAttribute("for", "relationship");
    relationship_label.textContent = "What is your relationship with the student? ";

    const relationship_select = document.createElement("select");
    relationship_select.id = "relationship";
    relationship_select.name = "relationship";
    relationship_select.required = true;

    const options = [
        {value: "Mother", text: "Mother"},
        {value: "Father", text: "Father"},
        {value: "Legal Guardian", text: "Legal Guardian"}
    ];

    options.forEach(option => {
        const elem_option = document.createElement("option");
        elem_option.value = option.value;
        elem_option.text = option.text;
        relationship_select.appendChild(elem_option);
    });

    add_container.appendChild(relationship_label);
    add_container.appendChild(relationship_select);
    add_container.appendChild(document.createElement("br"));
    add_container.appendChild(document.createElement("br"));

    const class_name_label = document.createElement("label");
    class_name_label.setAttribute("for", "class_name");
    class_name_label.textContent = "What year is your child? ";

    const class_select = document.createElement("select");
    class_select.id = "class_name";
    class_select.name = "class_name";
    class_select.required = true;

    const options2 = [
        {value: "Year 0", text: "Reception Year"},
        {value: "Year 1", text: "Year 1"},
        {value: "Year 2", text: "Year 2"},
        {value: "Year 3", text: "Year 3"},
        {value: "Year 4", text: "Year 4"},
        {value: "Year 5", text: "Year 5"},
        {value: "Year 6", text: "Year 6"}
    ];

    options2.forEach(option => {
        const elem_option = document.createElement("option");
        elem_option.value = option.value;
        elem_option.text = option.text;
        class_select.appendChild(elem_option);
    });

    add_container.appendChild(class_name_label);
    add_container.appendChild(class_select);
    add_container.appendChild(document.createElement("br"));
    add_container.appendChild(document.createElement("br"));

    const medical_label = document.createElement("label");
    medical_label.setAttribute("for", "no_medicals");
    medical_label.textContent = "How many medicals are you inserting? ";

    const no_medicals = document.createElement("input");
    no_medicals.type = "number";
    no_medicals.id = "no_medicals";
    no_medicals.name = "no_medicals";
    no_medicals.required = true;

    add_container.appendChild(medical_label);
    add_container.appendChild(no_medicals);
    add_container.appendChild(document.createElement("br"));
    add_container.appendChild(document.createElement("br"));

    const medical_container = document.createElement("div");
    medical_container.id = "medicals";
    no_medicals.addEventListener("change", () =>{
        medical_container.innerHTML = "";
        for(i=0; i < no_medicals.value; i++){
            const medical = document.createElement("input");
            medical.type = "text";
            medical.className = "medical"
            medical.required = true;
            medical_container.appendChild(medical);
            medical_container.appendChild(document.createElement("br"));
            medical_container.appendChild(document.createElement("br"));
        }
        add_container.appendChild(medical_container)
    })

}


add_form.addEventListener("submit", async (event)=>{
    event.preventDefault();

    let data;
    if(exists.value === "true"){
        const id = document.getElementById("id").value;
        const relationship = document.getElementById("relationship").value;
        data = {id,relationship,exists} 
    }else{
        const first_name = document.getElementById("first_name").value;
        const middle_initial = document.getElementById("middle_initial").value;
        const last_name = document.getElementById("last_name").value;
        const address = document.getElementById("address").value;
        const sex = document.getElementById("sex").value;
        const date_of_birth = document.getElementById("date_of_birth").value;
        const relationship = document.getElementById("relationship").value;
        const class_name = document.getElementById("class_name").value;

        const medicals = []
        const medical = document.querySelectorAll(".medical");

        medical.forEach((element) => {
            medicals.push(element.value);
        })
        data ={first_name, middle_initial, last_name, address, sex, date_of_birth, relationship, class_name, medicals}
    }

    const options = {
        method: "POST",
        body: JSON.stringify(data),
    };

    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/pupil/add.php", options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        setTimeout(() => {
            window.location.href = 'read_parent.html';
        }, 2000); 
    }
})