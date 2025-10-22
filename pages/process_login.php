<?php 

session_start();

include '../config/db.php';
include '../config/functions.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['password'] ?? '';

if (empty($email) || empty($senha)) {
    setFlash('Preencha todos os campos!');
    $stmt->close();
    $conn->close();
    header("Location: ../index.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    setFlash('Email ou senha incorretos!');
    $stmt->close();
    $conn->close();
    header("Location: ../index.php");
    exit;
}

$row = $result->fetch_assoc();
$hashDoBanco = $row['senha'];

if (password_verify($senha, $hashDoBanco)) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['user_name'] = $row['nome'];
    $stmt->close();
    $conn->close();
    header("Location: home.php");
    exit;
} else {
    setFlash('Email ou senha incorretos!');
    $stmt->close();
    $conn->close();
    header("Location: ../index.php");
    exit;
}
