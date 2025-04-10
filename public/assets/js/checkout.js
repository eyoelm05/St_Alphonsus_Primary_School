const class_name = document.getElementById("class_name");
const checkout_form = document.getElementById("checkout_form");
const submit_btn = document.getElementById("submit")

get_books(); 

let pupil_container = null
class_name.addEventListener("change", async () => {
    if (pupil_container) {
        pupil_container.remove();
        pupil_container = null;
    }

    pupil_container = document.createElement("div");
    pupil_container.id = "pupil_container";

    const options = {
        method: "GET",
    };
    const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/pupil/read_teacher.php?class_name=${class_name.value}`, options);
    const result = await response.json();

    const pupil_label = document.createElement("label");
    pupil_label.setAttribute("for", "pupil");
    pupil_label.textContent = "Pupil: ";

    const pupil_select = document.createElement("select");
    pupil_select.id = "pupil";
    pupil_select.name = "pupil";
    pupil_select.required = true;

    result.pupils.forEach(pupil => {
        const elem_option = document.createElement("option");
        elem_option.value = pupil.id;
        elem_option.text = `${pupil.id} ${pupil.name}`;
        pupil_select.appendChild(elem_option);
    });

    pupil_container.appendChild(pupil_label);
    pupil_container.appendChild(pupil_select);

    checkout_form.insertBefore(pupil_container, submit_btn)
})

async function get_books(){
    const options = {
        method: "GET",
    };
    const response = await fetch(`http://localhost/St_Alphonsus_Primary_School/api/books/read.php`, options);
    const result = await response.json();

    const book_label = document.createElement("label");
    book_label.setAttribute("for", "book");
    book_label.textContent = "Books: ";

    const book_select = document.createElement("select");
    book_select.id = "book";
    book_select.name = "book";
    book_select.required = true;

    result.books.forEach(book => {
        const elem_option = document.createElement("option");
        elem_option.value = book.isbn;
        elem_option.text = `${book.title} by ${book.author}: ${book.no_of_copies} available`;
        book_select.appendChild(elem_option);
    });

    checkout_form.insertBefore(book_label, submit_btn);
    checkout_form.insertBefore(book_select, submit_btn)
}

checkout_form.addEventListener("submit", async () => {
    event.preventDefault();

    const pupil_id = document.getElementById("pupil").value;
    const isbn = document.getElementById("book").value;

    const data = {isbn, pupil_id};

    const options = {
        method: "POST",
        body: JSON.stringify(data),
    };

    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/books/borrow.php", options);
    const result = await response.json();
    response_message.innerHTML = result.message;

    if(response.status == 200){
        response_message.className = "success"
        setTimeout(() => {
            const params = new URLSearchParams();
            params.append("id", pupil_id);
            location.href = "read_single.html?" + params.toString();
        }, 2000); 
    }else{
        response_message.className = "error"
    }
})