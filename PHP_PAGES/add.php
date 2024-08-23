<?php
include '../db.php';
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
        $pdf_path = '../uploads/' . $pdf_name;
        move_uploaded_file($pdf_tmp, $pdf_path);
    }

    $stmt = $conn->prepare("INSERT INTO clientes (inicio_vigencia, apolice, nome, cpf, numero, email, premio_liquido, comissao, status, seguradora, tipo_seguro, pdf_path, observacoes) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('sssssssssssss', $inicio_vigencia, $apolice, $nome, $cpf, $numero, $email, $premio_liquido, $comissao, $status, $seguradora, $tipo_seguro, $pdf_path, $observacoes);

    if ($stmt->execute()) {
        header('Location: ../index.php');
    } else {
        echo "Erro: " . $stmt->error;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Cliente</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
    <link rel="stylesheet" href="../CSS/add.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Adicionar Cliente</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inicio_vigencia"><i class="bi bi-calendar-day"></i> Início Vigência</label>
                    <input type="date" class="form-control" id="inicio_vigencia" name="inicio_vigencia" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="apolice"><i class="bi bi-file-earmark-text"></i> Proposta</label>
                    <input type="text" class="form-control" id="apolice" name="apolice" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nome"><i class="bi bi-person"></i> Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="cpf"><i class="bi bi-card-text"></i>CPF/CNPJ</label>
                    <input type="text" class="form-control" id="cpf" name="cpf" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="numero"><i class="bi bi-telephone"></i> Celular</label>
                    <input type="text" class="form-control" id="numero" name="numero" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="email"><i class="bi bi-envelope"></i> Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>
            <div class="form-row">
            <div class="form-group col-md-6">
                    <label for="premio_liquido"><i class="bi bi-currency-dollar"></i> Prêmio Líquido</label>
                    <input type="number" step="0.01" class="form-control" id="premio_liquido" name="premio_liquido"
                        required>
                </div>

                <div class="form-group col-md-6">
                    <label for="comissao"><i class="bi bi-percent"></i> Comissão (%)</label>
                    <input type="number" step="0.01" class="form-control" id="comissao" name="comissao" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="status"><i class="bi bi-tags"></i> Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="Efetivado">Aguardando Emissão</option>
                        <option value="Efetivado">Emitida</option>
                        <option value="Pendencia na vistoria">Pendência na vistoria</option>
                        <option value="Processo de Vistoria">Processo de Vistoria</option>
                        <option value="Processo de Vistoria">Pendencia de Proposta</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="seguradora"><i class="bi bi-building"></i> Seguradora</label>
                    <select class="form-control" id="seguradora" name="seguradora">
                        <option value="Aliro Seguro">Aliro Seguro</option>
                        <option value="Allianz Seguros">Allianz Seguros</option>
                        <option value="Azul Seguros">Azul Seguros</option>
                        <option value="HDI Seguros">HDI Seguros</option>
                        <option value="Liberty Seguros">Yelum</option>
                        <option value="MAPFRE">MAPFRE</option>
                        <option value="Unimed Seguros">Unimed Seguros</option>
                        <option value="Porto Seguro">Porto Seguro</option>
                        <option value="Sompo Auto">Sompo Auto</option>
                        <option value="Tokio Marine Seguros">Tokio Marine Seguros</option>
                        <option value="Zurich Brasil Seguros">Zurich Brasil Seguros</option>
                        <option value="Sancor Seguros">Sancor Seguros</option>
                        <option value="Suhai">Suhai</option>
                        <option value="Mitsui">Mitsui</option>
                        <option value="Sura Seguros">Sura Seguros</option>
                        <option value="EZZE">EZZE</option>
                        <option value="Capemisa">Capemisa</option>
                        <option value="AKAD">AKAD</option>
                        <option value="AssistCard">AssistCard</option>
                        <option value="AXA">AXA</option>
                        <option value="Ituran">Ituran</option>
                        <option value="Pottencial">Pottencial</option>
                        <option value="SulAmerica">SulAmerica</option>
                        <option value="VitalCard">VitalCard</option>
                        <option value="Bradesco">Bradesco</option>
                        <option value="ItauSeguros">ItauSeguros</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="tipo_seguro"><i class="bi bi-shield"></i> Tipo de Seguro</label>
                <select class="form-control" id="tipo_seguro" name="tipo_seguro">
                    <option value="Seguro Auto">Seguro Auto</option>
                    <option value="Seguro Residencial">Seguro Residencial</option>
                    <option value="Acidenes Pessoais">Acidenes Pessoais</option>
                    <option value="Seguro Moto">Seguro Moto</option>
                    <option value="Seguro de Vida">Seguro de Vida</option>
                    <option value="Seguro Empresarial">Seguro Empresarial</option>
                    <option value="Consórcio">Consórcio</option>
                    <option value="Seguro Transporte">Seguro Transporte</option>
                    <option value="Seguro Saúde">Seguro Saúde</option>
                    <option value="Seguro Dental">Seguro Dental</option>
                    <option value="Seguro Frota">Seguro Frota</option>
                    <option value="Seguro Agronegócio">Seguro Agronegócio</option>
                </select>
            </div>
            <div class="form-group">
                <label for="observacoes"><i class="bi bi-sticky"></i> Observações</label>
                <textarea class="form-control" id="observacoes" name="observacoes" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="pdf"><i class="bi bi-file-earmark-arrow-up"></i> Proposta PDF</label>
                <input type="file" class="form-control-file" id="pdf" name="pdf" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Adicionar</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="../JS/verificar_proposta.js"></script>
    
</body>

</html>