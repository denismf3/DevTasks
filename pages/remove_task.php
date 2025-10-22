<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');
session_start();
require_once '../config/db.php';
require_once '../config/functions.php';
protegerPagina();

session_start();
require_once '../config/db.php';
require_once '../config/functions.php';
protegerPagina();

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['ids'])) {
    echo json_encode(["status" => "error", "message" => "Nenhuma tarefa selecionada."]);
    exit;
}

$ids = $data['ids'];
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$usuario_id = $_SESSION['user_id'];

$sql = "DELETE FROM tarefas WHERE id IN ($placeholders) AND usuario_id = ?";
$stmt = $conn->prepare($sql);

$types = str_repeat('i', count($ids)) . 'i';
$params = [...$ids, $usuario_id];

$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "Falha ao excluir."]);
}
?>
