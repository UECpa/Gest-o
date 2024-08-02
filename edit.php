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
    
    $pdf_path = NULL;
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == UPLOAD_ERR_OK) {
        $pdf_name = $_FILES['pdf']['name'];
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_path = 'uploads/' . $pdf_name;
        move_uploaded_file($pdf_tmp, $pdf_path);
    }

    $sql = "UPDATE clientes SET 
            inicio_vigencia='$inicio_vigencia', apolice='$apolice', nome='$nome', cpf='$cpf', numero='$numero', email='$email', premio_liquido='$premio_liquido', comissao='$comissao', status='$status', pdf_path='$pdf_path'
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
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Editar Cliente</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Início Vigência</label>
            <input type="date" class="form-control" name="inicio_vigencia" value="<?php echo $row['inicio_vigencia']; ?>" required>
        </div>
        <div class="form-group">
            <label>Apólice</label>
            <input type="text" class="form-control" name="apolice" value="<?php echo $row['apolice']; ?>" required>
        </div>
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="nome" value="<?php echo $row['nome']; ?>" required>
        </div>
        <div class="form-group">
            <label>CPF</label>
            <input type="text" class="form-control" name="cpf" value="<?php echo $row['cpf']; ?>" required>
        </div>
        <div class="form-group">
            <label>Número</label>
            <input type="text" class="form-control" name="numero" value="<?php echo $row['numero']; ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
        </div>
        <div class="form-group">
            <label>Prêmio Líquido</label>
            <input type="number" step="0.01" class="form-control" name="premio_liquido" value="<?php echo $row['premio_liquido']; ?>" required>
        </div>
        <div class="form-group">
            <label>Comissão (%)</label>
            <input type="number" step="0.01" class="form-control" name="comissao" value="<?php echo $row['comissao']; ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="status">
                <option value="Efetivado" <?php if ($row['status'] == 'Efetivado') echo 'selected'; ?>>Efetivado</option>
                <option value="Cancelado" <?php if ($row['status'] == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                <option value="Recusa por vistoria" <?php if ($row['status'] == 'Recusa por vistoria') echo 'selected'; ?>>Recusa por vistoria</option>
                <option value="Processo de Vistoria" <?php if ($row['status'] == 'Processo de Vistoria') echo 'selected'; ?>>Processo de Vistoria</option>
            </select>
        </div>
        <div class="form-group">
            <label>Arquivo PDF</label>
            <input type="file" class="form-control-file" name="pdf">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
</div>
</body>
</html>
