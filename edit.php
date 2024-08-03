<?php
include 'db.php';

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
    
    $pdf_path = NULL;
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == UPLOAD_ERR_OK) {
        $pdf_name = $_FILES['pdf']['name'];
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_path = 'uploads/' . $pdf_name;
        move_uploaded_file($pdf_tmp, $pdf_path);
    }

    $sql = "UPDATE clientes SET 
            inicio_vigencia='$inicio_vigencia', apolice='$apolice', nome='$nome', cpf='$cpf', observacoes='$observacoes', numero='$numero', email='$email', premio_liquido='$premio_liquido', comissao='$comissao', status='$status', pdf_path='$pdf_path'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    $sql = "SELECT * FROM clientes WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
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
</body>
</html>
