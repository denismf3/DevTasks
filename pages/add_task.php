<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $prioridade = $_POST['prioridade'];
    $data_final = $_POST['data_final'] ?: null;
    $fixada = isset($_POST['fixada']) ? 1 : 0;
    $alerta = isset($_POST['alerta']) ? 1 : 0;
    $usuario_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tarefas (titulo, descricao, prioridade, data_final, fixada, alerta, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiii", $titulo, $descricao, $prioridade, $data_final, $fixada, $alerta, $usuario_id);
    $stmt->execute();

    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Tarefa</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/task.css">
</head>
<body>

    <header class="header-addtask">
        <button type="submit" class="btn-return" id="btnReturn">Voltar</button>
    </header>

    <section class="tarefas-box">
        <h2>Adicionar Nova Tarefa</h2>
        <form method="POST">
            <label><b>TÍTULO:</b></label><br>
            <input type="text" name="titulo" placeholder="Enter a title" required><br><br>

            <label><b>DESCRIÇÃO:</b></label><br>
            <textarea name="descricao" placeholder="Enter a descreption"></textarea><br><br>

            <label class="label-pri"><b>PRIORIDADE:</b></label><br>
            <select name="prioridade">
                <option value="Urgente">Urgente</option>
                <option value="Media">Média</option>
                <option value="Baixa">Baixa</option>
            </select><br><br>

            <label><b>DATA FINAL (opcional):</b></label><br>
            <input type="date" name="data_final"><br><br>

            <label><input type="checkbox" name="fixada"> Fixar no início</label><br>
            <label><input type="checkbox" name="alerta"> Mostrar alerta perto da data final</label><br><br>

            <button type="submit" class="btn-add">Salvar Tarefa</button>
        </form>
    </section>
    <script src="../js/return.js"></script>
</body>
</html>
