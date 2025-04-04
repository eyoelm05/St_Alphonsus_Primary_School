const update_form = document.getElementById("update_form");
const response_message = document.getElementById('response_message')
const submit_btn = document.getElementById("submit");
fetch_pupil()

async function fetch_pupil() {
    const params = new URLSearchParams(window.location.search);
    const id = params.get("id");

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
        document.getElementById("sex").value = result.pupil.sex;
    }
}