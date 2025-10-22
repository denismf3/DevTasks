<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dev_organizator";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Definir charset para evitar erro de acentuação
$conn->set_charset("utf8mb4");

// Checar conexão
if ($conn->connect_error) {
    // Exibir mensagem detalhada só em ambiente local
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        die("Conexão falhou: " . $conn->connect_error);
    } else {
        die("Erro ao conectar ao banco de dados.");
    }
}
?>
