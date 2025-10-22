<?php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$raw = file_get_contents('php://input');
$dados = json_decode($raw, true);
$ids = $dados['ids'] ?? [];

if (empty($ids)) {
    echo json_encode(["status" => "error", "message" => "Nenhuma tarefa selecionada"]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Usuário não autenticado"]);
    exit;
}

try {
    $conn->begin_transaction();

    foreach ($ids as $id) {
        $stmt = $conn->prepare("SELECT * FROM tarefas WHERE id = ?");
        if (!$stmt) throw new Exception("Erro prepare SELECT: " . $conn->error);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $tarefa = $result->fetch_assoc();
        $stmt->close();

        if (!$tarefa) continue; // pula se não encontrar a tarefa

        $insert = $conn->prepare("
            INSERT INTO tarefas_concluidas (titulo, descricao, prioridade, usuario_id, data_criacao, data_final)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        if (!$insert) throw new Exception("Erro prepare INSERT: " . $conn->error);

        $titulo = $tarefa['titulo'];
        $descricao = $tarefa['descricao'];
        $prioridade = $tarefa['prioridade'];
        $usuario_id = $_SESSION['user_id'];
        $data_criacao = $tarefa['data_criacao'] ?? null;

        $insert->bind_param("sssis", $titulo, $descricao, $prioridade, $usuario_id, $data_criacao);
        $ok = $insert->execute();
        $insert->close();

        if (!$ok) throw new Exception("Erro ao inserir tarefa concluída: " . $conn->error);

        $del = $conn->prepare("DELETE FROM tarefas WHERE id = ?");
        if (!$del) throw new Exception("Erro prepare DELETE: " . $conn->error);
        $del->bind_param("i", $id);
        $del_ok = $del->execute();
        $del->close();

        if (!$del_ok) throw new Exception("Erro ao deletar tarefa: " . $conn->error);
    }

    $conn->commit();
    echo json_encode(["status" => "success", "message" => "Tarefas concluídas com sucesso!"]);
    exit;

} catch (Exception $e) {
    if (isset($conn) && $conn->connect_errno === 0) {
        $conn->rollback();
    }
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    exit;
}
