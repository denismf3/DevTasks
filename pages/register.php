<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" type="text/css" href="../css/estilo.css">
</head>

<body>
    <header>
        <h2>Crie sua conta</h2>
    </header>

    <section class="sec-register">
        <div class="register-box">
            <h1>Crie sua conta</h1>

            <form id="form-register" action="process_register.php" method="post">
                <label for="nome">Name:</label><br>
                <input type="text" id="nome" name="nome" placeholder="Insert your name" required><br><br>

                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" placeholder="Insert your email" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" placeholder="Insert your password" required><br><br>

                <label for="confirm_password">Confirm your password:</label><br>
                <input type="password" id="confirm_password" name="confirma_senha" placeholder="Insert again your password" required><br><br>

                <div id="mensagem" style="margin-bottom: 10px; color: red;"></div>

                <button type="submit">Registrar</button>
            </form>
        </div>
    </section>

    <script src="../js/register.js"></script>
</body>

</html>
