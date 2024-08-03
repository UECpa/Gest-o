<?php
include 'db.php';
session_start(); // Inicie a sessão

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
    $observacoes = $_POST['observacoes'];

    // Registrar a notificação
    $usuario_id = $_SESSION['user_id'];
    date_default_timezone_set('America/Sao_Paulo');
    // Obtenha o nome do usuário
    $stmt = $conn->prepare("SELECT nome FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $usuario_nome = $user_result->fetch_assoc()['nome'];

    $mensagem = "Usuário $usuario_nome adicionou proposta de $nome - " . date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO notificacoes (usuario_id, mensagem, data_hora) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $usuario_id, $mensagem, $mensagem);
    $stmt->execute();
    
    $pdf_path = NULL;
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == UPLOAD_ERR_OK) {
        $pdf_name = $_FILES['pdf']['name'];
        $pdf_tmp = $_FILES['pdf']['tmp_name'];
        $pdf_path = 'uploads/' . $pdf_name;
        move_uploaded_file($pdf_tmp, $pdf_path);
    }

    $stmt = $conn->prepare("INSERT INTO clientes (inicio_vigencia, apolice, nome, cpf, numero, email, premio_liquido, comissao, status, seguradora, tipo_seguro, pdf_path, observacoes) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssssssss', $inicio_vigencia, $apolice, $nome, $cpf, $numero, $email, $premio_liquido, $comissao, $status, $seguradora, $tipo_seguro, $pdf_path, $observacoes);
    
    if ($stmt->execute()) {
        header('Location: index.php');
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="add.css"> <!-- Adicione o link para o CSS -->
</head>
<body>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Adicionar Cliente</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label><i class="bi bi-calendar-day"></i> Início Vigência</label>
                <input type="date" class="form-control" name="inicio_vigencia" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-file-earmark-text"></i> Proposta</label>
                <input type="text" class="form-control" name="apolice" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-person"></i> Nome</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-card-text"></i> CPF</label>
                <input type="text" class="form-control" name="cpf" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-telephone"></i> Celular</label>
                <input type="number" class="form-control" name="numero" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-envelope"></i> Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-currency-dollar"></i> Prêmio Líquido</label>
                <input type="number" step="0.01" class="form-control" name="premio_liquido" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-percent"></i> Comissão (%)</label>
                <input type="number" step="0.01" class="form-control" name="comissao" required>
            </div>
            <div class="form-group">
                <label><i class="bi bi-tags"></i> Status</label>
                <select class="form-control" name="status">
                    <option value="Efetivado">Efetivado</option>
                    <option value="Cancelado">Cancelado</option>
                    <option value="Recusa por vistoria">Recusa por vistoria</option>
                    <option value="Processo de Vistoria">Processo de Vistoria</option>
                </select>
            </div>
            <div class="form-group">
    <label><i class="bi bi-building"></i> Seguradora</label>
    <select class="form-control" name="seguradora">
        <option value="Aliro Seguro"><i class="bi bi-shield"></i> Aliro Seguro</option>
        <option value="Allianz Seguros"><i class="bi bi-shield"></i> Allianz Seguros</option>
        <option value="Azul Seguros"><i class="bi bi-shield"></i> Azul Seguros</option>
        <option value="HDI Seguros"><i class="bi bi-shield"></i> HDI Seguros</option>
        <option value="Liberty Seguros"><i class="bi bi-shield"></i> Liberty Seguros</option>
        <option value="MAPFRE"><i class="bi bi-shield"></i> MAPFRE</option>
        <option value="Porto Seguro"><i class="bi bi-shield"></i> Porto Seguro</option>
        <option value="Sompo Auto"><i class="bi bi-shield"></i> Sompo Auto</option>
        <option value="Tokio Marine Seguros"><i class="bi bi-shield"></i> Tokio Marine Seguros</option>
        <option value="Zurich Brasil Seguros"><i class="bi bi-shield"></i> Zurich Brasil Seguros</option>
        <option value="Sancor Seguros"><i class="bi bi-shield"></i> Sancor Seguros</option>
        <option value="Suhai"><i class="bi bi-shield"></i> Suhai</option>
        <option value="Mitsui"><i class="bi bi-shield"></i> Mitsui</option>
        <option value="Sura Seguros"><i class="bi bi-shield"></i> Sura Seguros</option>
        <option value="EZZE"><i class="bi bi-shield"></i> EZZE</option>
    </select>
</div>

<div class="form-group">
    <label><i class="bi bi-shield"></i> Tipo de Seguro</label>
    <select class="form-control" name="tipo_seguro">
        <option value="Seguro Auto"><i class="bi bi-car"></i> Seguro Auto</option>
        <option value="Seguro Moto"><i class="bi bi-motorcycle"></i> Seguro Moto</option>
        <option value="Seguro de Vida"><i class="bi bi-heart"></i> Seguro de Vida</option>
        <option value="Seguro Empresarial"><i class="bi bi-building"></i> Seguro Empresarial</option>
        <option value="Consórcio"><i class="bi bi-handshake"></i> Consórcio</option>
        <option value="Seguro Transporte"><i class="bi bi-truck"></i> Seguro Transporte</option>
        <option value="Seguro Saúde"><i class="bi bi-cross"></i> Seguro Saúde</option>
        <option value="Seguro Dental"><i class="bi bi-tooth"></i> Seguro Dental</option>
        <option value="Seguro Frota"><i class="bi bi-car-front"></i> Seguro Frota</option>
        <option value="Seguro Agronegócio"><i class="bi bi-tractor"></i> Seguro Agronegócio</option>
    </select>
</div>

            <div class="form-group">
                <label><i class="bi bi-sticky"></i> Observações</label>
                <textarea class="form-control" name="observacoes" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label><i class="bi bi-file-earmark-arrow-up"></i> Arquivo PDF</label>
                <input type="file" class="form-control-file" name="pdf">
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Adicionar</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="verificar_proposta.js"></script>
</body>
</html>

</body>
</html>