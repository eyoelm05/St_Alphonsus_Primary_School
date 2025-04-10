const container = document.getElementById("container");
const pupil_id = document.getElementById("id");
const name = document.getElementById("name");
const parents = document.getElementById("parents");
const date_of_birth = document.getElementById("date_of_birth");
const sex = document.getElementById("sex");
const address = document.getElementById("address");
const class_name = document.getElementById("class");
const class_teacher = document.getElementById("class_teacher");
const update_btn = document.getElementById("update_btn");
const borrowed_books = document.getElementById("borrowed_books");
const borrowed_books_section = document.getElementById("borrowed_books_section");

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
    name.textContent += `${pupil.first_name} ${pupil.middle_initial} ${pupil.last_name}`;
    address.textContent += pupil.address;
    
    pupil.parents.forEach(parent => {
        const h3 = document.createElement("h3");
        h3.textContent = parent;
        parents.appendChild(h3);
    });
   
    date_of_birth.textContent += pupil.date_of_birth;
    sex.textContent += pupil.sex;
    class_name.textContent += pupil.class;
    class_teacher.textContent += pupil.teacher_name;

    if (pupil.teacher_assistants[0] !== "") {
        const ta_container = document.createElement("div");
        ta_container.id = "teacher_assistant";
        
        const heading = document.createElement("h3");
        heading.textContent = "Teacher Assistants:";
        ta_container.appendChild(heading);
        
        pupil.teacher_assistants.forEach(assistant => {
            if (assistant) {
                const assistant_span = document.createElement("span");
                assistant_span.className = "assistant";
                assistant_span.textContent = assistant;
                ta_container.appendChild(assistant_span);
            }
        });
        
        container.insertBefore(ta_container, borrowed_books_section);
    }
    
    if (pupil.medicals.length > 0) {
        const medicals_container = document.createElement("div");
        medicals_container.id = "medicals";
        
        const heading = document.createElement("h3");
        heading.textContent = "Medical Conditions: ";
        medicals_container.appendChild(heading);
        
        pupil.medicals.forEach(medical => {
            const medical_elem = document.createElement("span");
            medical_elem.className = "medical";
            medical_elem.textContent = medical;
            medicals_container.appendChild(medical_elem);
        });
        
        container.insertBefore(medicals_container, borrowed_books_section);
    }
    
    if (pupil.borrowed_books.length > 0) {
        borrowed_books.innerHTML = ""; 
        
        pupil.borrowed_books.forEach(book => {
            const book_card = document.createElement("div");
            book_card.id = "card";
                        
            const book_title = document.createElement("p");
            book_title.id = "title";
            book_title.textContent = book.title;
            
            const book_author = document.createElement("p");
            book_author.id = "author";
            book_author.textContent = `by ${book.author}`;
            
            const book_isbn = document.createElement("p");
            book_isbn.id = "isbn";
            book_isbn.textContent = `ISBN: ${book.isbn}`;
                        
            const borrowed_date = document.createElement("p");
            borrowed_date.textContent = `Borrowed: ${book.borrowed_date}`;
            
            const due_date_elem = document.createElement("p");
            due_date_elem.textContent = `Due: ${book.due_date}`;
            
            const book_status = document.createElement("div");
            book_status.id = "status";
            
            const today = new Date();
            const dueDate = new Date(book.due_date);

            if (book.date_returned) {
                book_status.classList.add("returned");
                book_status.textContent = `Returned on ${book.date_returned}`;
            } else if (dueDate < today) {
                book_status.classList.add("overdue");
                book_status.textContent = "Overdue";
            } else {
                book_status.classList.add("active");
                book_status.textContent = "Active";
            }
            
            book_card.appendChild(book_title);
            book_card.appendChild(book_author);
            book_card.appendChild(book_isbn);
            book_card.appendChild(borrowed_date);
            book_card.appendChild(due_date_elem);
            book_card.appendChild(book_status);

            if(result.user_type === "employee" && !book.date_returned){
                const confirm_return = document.createElement("a");
                confirm_return.id = "confirm_return";
                confirm_return.textContent = "Confirm Return";

                book_card.appendChild(confirm_return)
                confirm_return.addEventListener("click", async () => {
                    const data = {
                        pupil_id: pupil.id,
                        isbn: book.isbn
                    }
                    const options = {
                        method: "POST",
                        body: JSON.stringify(data),
                    };
                    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/books/return.php", options);
                    if(response.status === 200){
                        location.reload()
                    }
                })
            }

            borrowed_books.appendChild(book_card);

        });
    } else {
        borrowed_books_section.style.display = "none";
    }
    
    if(result.user_type === "parent"){
        const update_pupil = document.createElement("a");
        update_pupil.href = `update_pupil.html?id=${id}`;
        update_pupil.id = "update_pupil";
        update_pupil.textContent = "Update Pupil";
        update_btn.appendChild(update_pupil);
    }
}

read_single();