<?php
// Verifica se a sessão já está iniciada antes de chamar session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';
$error = ''; // Inicializa a variável de erro

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Usa prepared statement para evitar SQL Injection
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nome'] = $usuario['nome']; // Armazena o nome do usuário na sessão
            header('Location: index.php');
            exit();
        } else {
            $error = "Senha incorreta.";
        }
    } else {
        $error = "Nenhuma conta encontrada com este email.";
    }
    $stmt->close();
}
$anoAtual = date("Y");
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="CSS/login.css">
</head>

<body>
    <div class="container mt-5">
        <img src="IMG/logo.png" alt="Logo da Empresa" class="logo"> <!-- Logo da empresa -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="senha"><i class="fas fa-lock"></i> Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login</button>
        </form>
    </div>

    <!-- Modal de Registro -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Registrar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="PHP_PAGES/register.php">
                        <div class="form-group">
                            <label for="modal-nome"><i class="fas fa-user"></i> Nome</label>
                            <input type="text" class="form-control" id="modal-nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="modal-email"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" class="form-control" id="modal-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="modal-senha"><i class="fas fa-lock"></i> Senha</label>
                            <input type="password" class="form-control" id="modal-senha" name="senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i>
                            Registrar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>