<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Lê o filtro selecionado
$filtro = $_GET['filtro'] ?? 'todas';

// Define a consulta SQL dependendo do filtro
if ($filtro === 'andamento') {
    $sql = "SELECT * FROM tarefas WHERE usuario_id = ? ORDER BY id DESC";
} elseif ($filtro === 'concluidas') {
    $sql = "SELECT * FROM tarefas_concluidas WHERE usuario_id = ? ORDER BY id DESC";
} else { // todas
    // UNION junta as duas tabelas
    $sql = "
        SELECT id, titulo, descricao, prioridade, data_final, fixada, 'andamento' AS tipo
        FROM tarefas WHERE usuario_id = ?
        UNION ALL
        SELECT id, titulo, descricao, prioridade, data_final, 0 AS fixada, 'concluida' AS tipo
        FROM tarefas_concluidas WHERE usuario_id = ?
        ORDER BY id DESC
    ";
}

$stmt = $conn->prepare($sql);

if ($filtro === 'todas') {
    $stmt->bind_param("ii", $user_id, $user_id);
} else {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>Ver Tarefas</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/task.css">
</head>
<body>
<header class="header-seetask">
    <h1>Minhas Tarefas</h1>
    <div class="back-area">
        <p><b>Deseja voltar?</b></p>
        <button type="submit" class="btn-seereturn" id="btnReturn">Voltar</button>
    </div>
</header>

<section class="tarefas-box">
    <div class="filtro-box">
        <form method="get">
            <label for="filtro"><b>Filtrar:</b></label>
            <select name="filtro" id="filtro" onchange="this.form.submit()">
                <option value="todas" <?= $filtro === 'todas' ? 'selected' : '' ?>>Todas</option>
                <option value="andamento" <?= $filtro === 'andamento' ? 'selected' : '' ?>>Em andamento</option>
                <option value="concluidas" <?= $filtro === 'concluidas' ? 'selected' : '' ?>>Concluídas</option>
            </select>
        </form>
    </div>

    <div class="tarefas-container">
        <?php if ($result->num_rows === 0): ?>
            <p>Nenhuma tarefa encontrada.</p>
        <?php else: ?>
            <?php while ($tarefa = $result->fetch_assoc()): ?>
                <?php
                $classe = strtolower($tarefa['prioridade'] ?? 'media');
                if (($tarefa['tipo'] ?? '') === 'concluida') {
                    $classe .= ' concluida';
                }
                ?>
                <div class="post-it <?= $classe; ?> <?= !empty($tarefa['fixada']) ? 'fixada' : '' ?>" data-id="<?= $tarefa['id'] ?>">
                    <h3><?= htmlspecialchars($tarefa['titulo']); ?></h3>
                    <p><?= htmlspecialchars($tarefa['descricao']); ?></p>
                    <?php if (($tarefa['tipo'] ?? '') === 'andamento'): ?>
                        <button class="btn-conclude">Concluir ✅</button>
                    <?php else: ?>
                        <small><i>Tarefa concluída</i></small>
                    <?php endif; ?>
                    <?php if (!empty($tarefa['data_final'])): ?>
                        <small>Finaliza em: <?= date('d/m/Y', strtotime($tarefa['data_final'])); ?></small>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>

<script src="../js/return.js"></script>
</body>
</html>
