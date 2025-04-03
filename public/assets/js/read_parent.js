const container = document.getElementById("container");

async function read_parent() {
    const options = {
        method: "POST",
        body: JSON.stringify(data),
    };
    const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/pupil/read_parent.php", options);
    const result = await response.json();

    if(response == 404){
        
    }
}
