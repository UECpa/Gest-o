<?php
include 'db.php';
session_start(); // Certifique-se de que a sessão está iniciada

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inicio_vigencia = $_POST['inicio_vigencia'];
    $apolice = $_POST['apolice'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $numero = $_POST['numero'];
    $email = $_POST['email'];
    $premio_liquido = $_POST['premio_liquido'];
    $comissao = $_POST['comissao'];
    $status = $_POST['status'];
    $observacoes = $_POST['observacoes'];

    // Registrar a notificação
    $usuario_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $usuario_nome = $user_result->fetch_assoc()['nome'];
    date_default_timezone_set('America/Sao_Paulo');

    $stmt = $conn->prepare("SELECT nome FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $cliente_result = $stmt->get_result();
    $nome_cliente = $cliente_result->fetch_assoc()['nome'];

    $mensagem = "Usuário $usuario_nome atualizou proposta de $nome_cliente - " . date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO notificacoes (usuario_id, mensagem, data_hora) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $usuario_id, $mensagem, date('Y-m-d H:i:s'));
    $stmt->execute();

    $pdf_path = NULL;
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == UPLOAD_ERR_OK) {
        $pdf_name = $_FILES['pdf']['name'];
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_path = 'uploads/' . basename($pdf_name);
        move_uploaded_file($pdf_tmp, $pdf_path);
    }

    $stmt = $conn->prepare("UPDATE clientes SET inicio_vigencia = ?, apolice = ?, nome = ?, cpf = ?, observacoes = ?, numero = ?, email = ?, premio_liquido = ?, comissao = ?, status = ?, pdf_path = ? WHERE id = ?");
    $stmt->bind_param("sssssssssdsi", $inicio_vigencia, $apolice, $nome, $cpf, $observacoes, $numero, $email, $premio_liquido, $comissao, $status, $pdf_path, $id);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="edit.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Editar Cliente</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inicio_vigencia">Início Vigência</label>
            <input type="date" class="form-control" id="inicio_vigencia" name="inicio_vigencia" value="<?php echo htmlspecialchars($row['inicio_vigencia']); ?>" required>
        </div>
        <div class="form-group">
            <label for="apolice">Apólice</label>
            <input type="text" class="form-control" id="apolice" name="apolice" value="<?php echo htmlspecialchars($row['apolice']); ?>" required>
        </div>
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label for="cpf">CPF</label>
            <input type="text" class="form-control" id="cpf" name="cpf" value="<?php echo htmlspecialchars($row['cpf']); ?>" required>
        </div>
        <div class="form-group">
            <label for="numero">Número</label>
            <input type="number" class="form-control" id="numero" name="numero" value="<?php echo htmlspecialchars($row['numero']); ?>" required>
        </div>
        <div class="form-group">
            <label for="observacoes">Observações</label>
            <input type="text" class="form-control" id="observacoes" name="observacoes" value="<?php echo htmlspecialchars($row['observacoes']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="premio_liquido">Prêmio Líquido</label>
            <input type="number" step="0.01" class="form-control" id="premio_liquido" name="premio_liquido" value="<?php echo htmlspecialchars($row['premio_liquido']); ?>" required>
        </div>
        <div class="form-group">
            <label for="comissao">Comissão (%)</label>
            <input type="number" step="0.01" class="form-control" id="comissao" name="comissao" value="<?php echo htmlspecialchars($row['comissao']); ?>" required>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="Efetivado" <?php if ($row['status'] == 'Efetivado') echo 'selected'; ?>>Efetivado</option>
                <option value="Cancelado" <?php if ($row['status'] == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                <option value="Recusa por vistoria" <?php if ($row['status'] == 'Recusa por vistoria') echo 'selected'; ?>>Recusa por vistoria</option>
                <option value="Processo de Vistoria" <?php if ($row['status'] == 'Processo de Vistoria') echo 'selected'; ?>>Processo de Vistoria</option>
            </select>
        </div>
        <div class="form-group">
            <label for="pdf">Arquivo PDF</label>
            <input type="file" class="form-control-file" id="pdf" name="pdf">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Atualizar
        </button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="verificar_proposta.js"></script>
</body>
</html>
