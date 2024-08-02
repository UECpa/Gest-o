<?php
include 'db.php';

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
    $seguradora = $_POST['seguradora'];
    $tipo_seguro = $_POST['tipo_seguro'];
    
    $pdf_path = NULL;
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == UPLOAD_ERR_OK) {
        $pdf_name = $_FILES['pdf']['name'];
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_path = 'uploads/' . $pdf_name;
        move_uploaded_file($pdf_tmp, $pdf_path);
    }

    $sql = "INSERT INTO clientes (inicio_vigencia, apolice, nome, cpf, numero, email, premio_liquido, comissao, status, seguradora, tipo_seguro, pdf_path) 
            VALUES ('$inicio_vigencia', '$apolice', '$nome', '$cpf', '$numero', '$email', '$premio_liquido', '$comissao', '$status', '$seguradora', '$tipo_seguro', '$pdf_path')";
    
    if ($conn->query($sql) === TRUE) {
        header('Location: index.php');
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos gerais do corpo */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    color: #343a40;
    margin: 0;
    padding: 0;
}

/* Estilo do contêiner */
.container {
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

/* Estilo dos títulos */
h2 {
    color: #007bff;
    margin-bottom: 20px;
}

/* Estilo do formulário */
form {
    max-width: 600px;
    margin: 0 auto;
}

/* Estilo dos inputs e selects */
.form-control, .form-control-file {
    border-radius: 0.25rem;
    border: 1px solid #ced4da;
    padding: 10px;
    margin-bottom: 15px;
}

.form-control:focus, .form-control-file:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

/* Estilo do botão */
.btn-primary {
    background-color: #007bff;
    border: none;
    border-radius: 0.25rem;
    padding: 10px 20px;
}

.btn-primary:hover {
    background-color: #0056b3;
    color: #ffffff;
}

    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Adicionar Cliente</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Início Vigência</label>
            <input type="date" class="form-control" name="inicio_vigencia" required>
        </div>
        <div class="form-group">
            <label>Apólice</label>
            <input type="text" class="form-control" name="apolice" required>
        </div>
        <div class="form-group">
            <label>Nome</label>
            <input type="text" class="form-control" name="nome" required>
        </div>
        <div class="form-group">
            <label>CPF</label>
            <input type="text" class="form-control" name="cpf" required>
        </div>
        <div class="form-group">
            <label>Celular</label>
            <input type="text" class="form-control" name="numero" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="form-group">
            <label>Prêmio Líquido</label>
            <input type="number" step="0.01" class="form-control" name="premio_liquido" required>
        </div>
        <div class="form-group">
            <label>Comissão (%)</label>
            <input type="number" step="0.01" class="form-control" name="comissao" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="status">
                <option value="Efetivado">Efetivado</option>
                <option value="Cancelado">Cancelado</option>
                <option value="Recusa por vistoria">Recusa por vistoria</option>
                <option value="Processo de Vistoria">Processo de Vistoria</option>
            </select>
        </div>
        <div class="form-group">
            <label>Seguradora</label>
            <input type="text" class="form-control" name="seguradora" required>
        </div>
        <div class="form-group">
            <label>Tipo de Seguro</label>
            <select class="form-control" name="tipo_seguro">
                <option value="Auto">Auto</option>
                <option value="Residencia">Residência</option>
                <option value="Vida">Vida</option>
                <option value="Acidentes pessoais">Acidentes Pessoais</option>
                <option value="Consorcio">Consórcio</option>
                <option value="Bike">Bike</option>
            </select>
        </div>
        <div class="form-group">
            <label>Arquivo PDF</label>
            <input type="file" class="form-control-file" name="pdf">
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
</div>
</body>
</html>
