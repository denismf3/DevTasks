<?php
include 'config/functions.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/estilo.css">
    <link rel="stylesheet" href="css/global.css">
    <title>DEV ORGANIZATOR</title>
</head>
<body>

<header>
    <h3>DEV ORGANIZATOR</h3>
    <h4>A AGENDA DO DEV JUNIOR</h4>
</header>

<section class="sec1">
    <h1>Entre com sua conta:</h1><br>
    <div class="container">
        <div class="form1">
            <form id="form-login" method="post" action="pages/process_login.php">
                <label for="email"><b>Email:</b></label><br>
                <input type="email" id="email" name="email" placeholder="Insert your email" required><br><br>

                <label for="password"><b>Password:</b></label><br>
                <input type="password" id="password" name="password" placeholder="Insert your password" required><br><br>

                <?php exibirFlash(); ?>

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</section>

<section class="sec2">
    <div class="form2">
        <h1>Ainda não tem uma conta?</h1><br>
        <button id="btn-register"><b>Registrar</b></button>
    </div>
</section>

<footer>
    <h3>O SITE QUE FAZ A GESTÃO DAS TAREFAS DOS DEV's</h3>
    <br>
    <h3>GITHUB: DenisMF3</h3>
    <br>
    <h4>Administre melhor seu tempo</h4>
    <p>@dg.mjj</p>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnRegister = document.getElementById("btn-register");
    if (btnRegister) {
        btnRegister.addEventListener("click", function () {
            window.location.href = "pages/register.php";
        });
    }
});
</script>

</body>
</html>
