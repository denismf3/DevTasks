<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


function usuarioLogado() {
    return isset($_SESSION['user_id']);
}

function protegerPagina() {
    if (!usuarioLogado()) {
        $_SESSION['flash'] = 'Você precisa fazer login primeiro!';
        header("Location: ../index.php");
        exit;
    }
}

function setFlash($mensagem) {
    $_SESSION['flash'] = $mensagem;
}

function exibirFlash() {
    if (isset($_SESSION['flash'])) {
        echo '<div class="mensagem">' . $_SESSION['flash'] . '</div>';
        unset($_SESSION['flash']);
    }
}
?>
