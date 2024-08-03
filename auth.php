<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifica se o nome do usuário está definido na sessão
if (!isset($_SESSION['user_nome'])) {
    $_SESSION['user_nome'] = 'Usuário'; // Define um nome padrão, se necessário
}
?>
