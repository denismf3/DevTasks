<?php
include '../config/db.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['password'];
    $confirma = $_POST['confirma_senha'];

    if($senha !== $confirma){
        echo "<script>alert('As senhas não coincidem!');window.history.back();</script>";
        exit;
    }

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome, $email, $senha_hash);

    if($stmt->execute()){
        echo "<script>alert('Cadastro realizaddo com sucesso!'); window.location.href='../index.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar: " . $conn->error . "'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>