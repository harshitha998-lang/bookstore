function toggleForm() {
    document.getElementById("login-form").style.display = 
        document.getElementById("login-form").style.display === "none" ? "block" : "none";
    document.getElementById("signup-form").style.display = 
        document.getElementById("signup-form").style.display === "none" ? "block" : "none";
}

function signup() {
    let name = document.getElementById("signup-name").value;
    let email = document.getElementById("signup-email").value;
    let password = document.getElementById("signup-password").value;

    fetch("signup.php", {
        method: "POST",
        body: JSON.stringify({ name, email, password }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("signup-msg").textContent = data.message;
         if (data.success)window.location.href = "index.php";
    });
}

function login() {
    let email = document.getElementById("login-email").value;
    let password = document.getElementById("login-password").value;

    fetch("login.php", {
        method: "POST",
        body: JSON.stringify({ email, password }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("login-msg").textContent = data.message;
        if (data.success) window.location.href = "index.php";
    });
}