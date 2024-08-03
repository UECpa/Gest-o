<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuarios (nome, email, senha) VALUES ('$nome', '$email', '$senha')";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: ../login.php');
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../CSS/register.css"> <!-- Adicione o link para o CSS -->
</head>
<body>
<div class="container mt-5">
    <img src="logo.png" alt="Logo da Empresa" class="logo"> <!-- Logo da empresa -->
    <h2 class="text-center">Registrar</h2>
    <form method="POST" action="register.php">
        <div class="form-group">
            <label for="nome"><i class="fas fa-user"></i> Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="form-group">
            <label for="email"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="senha"><i class="fas fa-lock"></i> Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Registrar</button>
    </form>
    <p class="text-center mt-3">
        <i class="fas fa-sign-in-alt"></i> <a href="../login.php">Já tem uma conta? Faça login aqui</a>
    </p>
</div>
</body>
</html>
