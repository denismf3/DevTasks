<?php
session_start();
require_once '../config/db.php';
require_once '../config/functions.php';
protegerPagina();

$nomeCompleto = $_SESSION['user_name'];
$partes = explode(' ', trim($nomeCompleto));
$primeirosNomes = implode(' ', array_slice($partes, 0, 2));

$usuario_id = $_SESSION['user_id'];
$query = "SELECT * FROM tarefas 
          WHERE usuario_id = ? 
          ORDER BY fixada DESC,
          FIELD(prioridade, 'Urgente', 'Media', 'Baixa')
          LIMIT 12";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/task.css">
    <title>Home</title>
</head>

<body>

    <header class="header-title">
        <div class="header-left">
            <h1>Bem-vindo, <?= htmlspecialchars($primeirosNomes); ?>!</h1>
        </div>
        <div class="header-right">
            <p><b>Deseja sair?</b></p>
            <button type="submit" class="btn-logout" id="btnLogout">Sair</button>
        </div>
    </header>

    <section>
        <section class="tarefas-box">
            <div class="div-seetask">
                <button type="submit" class="btn-seetask" id="btnSeeTask">Ver Tarefas</button>
            </div>

            <h2>Minhas Tarefas</h2>

            <div class="botoes-concluir">
                <button id="btnConclude" class="btn-conclude">✅ Concluir</button>
                <button id="btnConfirmConclude" class="btn-confirm-c">Confirmar Conclusão</button>
            </div>

            <div class="botoes-remocao">
                <button id="btnRemove" class="btn-remove">🗑️ Remover</button>
                <button id="btnConfirmRemove" class="btn-confirm-r">Confirmar Remoção</button>
            </div>

            <div class="tarefas-container">
                <?php while ($tarefa = $result->fetch_assoc()): ?>
                    <div class="post-it <?= strtolower($tarefa['prioridade']); ?> <?= $tarefa['fixada'] ? 'fixada' : '' ?>"
                        data-id="<?= $tarefa['id'] ?>">
                        
                        <input type="checkbox" class="checkbox-conclude" style="display:none;">

                        <input type="checkbox" class="checkbox-remover" style="display:none;">

                        <h3><?= htmlspecialchars($tarefa['titulo']); ?></h3>
                        <p><?= htmlspecialchars($tarefa['descricao']); ?></p>

                        <?php if ($tarefa['data_final']): ?>
                            <small>Finaliza em: <?= date('d/m/Y', strtotime($tarefa['data_final'])); ?></small>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>

            <a href="add_task.php" class="btn-add">Adicionar Tarefa</a>
        </section>
    </section>

    <script src="../js/see_task.js"></script>
    <script src="../js/conclude.js"></script>
    <script src="../js/remove_task.js"></script>
    <script src="../js/logout.js"></script>
</body>

</html>
