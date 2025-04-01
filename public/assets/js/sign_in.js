async function sign_in(event) {
    event.preventDefault(); 

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const data = {
        "username": username,
        "password": password
    };

    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    };

    try {
        const response = await fetch("http://localhost/St_Alphonsus_Primary_School/api/users/login.php", options);
        
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const result = await response.json();
        console.log(result);
    } catch (error) {
        console.error("Error:", error);
    }
}
document.getElementById('loginForm').addEventListener('submit', sign_in);
