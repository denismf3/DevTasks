document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-register");

    form.addEventListener("submit", function (event) {
        

        const senha = document.getElementById("password").value;
        const confirma = document.getElementById("confirm_password").value;
        const mensagem = document.getElementById("mensagem");

        mensagem.textContent = "";

        if (senha !== confirma) {
            event.preventDefault();
            event.preventDefault();
            mensagem.textContent = "As senhas não coincidem!";
            mensagem.style.color = "red";
        }
    });
});
