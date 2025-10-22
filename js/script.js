document.addEventListener("DOMContentLoaded", function () {
    const btnRegister = document.getElementById("btn-register");

    if (btnRegister) {
        btnRegister.addEventListener("click", function () {
            window.location.href = "/programacao-web/pages/register.php";
        });
    }


    const formLogin = document.getElementById("form-login");

    if (formLogin) {
        formLogin.addEventListener("submit", function (event) {
            event.preventDefault();

            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;

            if (email && password) {
                window.location.href = "/programacao-web/pages/home.php";
            } else {
                alert("Por favor, preencha todos os campos.");
            }
        });
    }
});