<?php
include '../db.php';

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$searchName = isset($_GET['name']) ? $_GET['name'] : '';
$searchCpf = isset($_GET['cpf']) ? $_GET['cpf'] : '';
$searchStatus = isset($_GET['status']) ? $_GET['status'] : '';

// Atualize a consulta SQL para incluir a busca
$sql = "SELECT * FROM clientes WHERE MONTH(inicio_vigencia) = '$month' AND YEAR(inicio_vigencia) = '$year'";

if ($searchName) {
    $searchName = $conn->real_escape_string($searchName);
    $sql .= " AND nome LIKE '%$searchName%'";
}

if ($searchCpf) {
    $searchCpf = $conn->real_escape_string($searchCpf);
    $sql .= " AND cpf LIKE '%$searchCpf%'";
}

if ($searchStatus && $searchStatus !== 'Todos') {
    $searchStatus = $conn->real_escape_string($searchStatus);
    $sql .= " AND status = '$searchStatus'";
}

$result = $conn->query($sql);

$months = [
    '01' => 'Janeiro',
    '02' => 'Fevereiro',
    '03' => 'Março',
    '04' => 'Abril',
    '05' => 'Maio',
    '06' => 'Junho',
    '07' => 'Julho',
    '08' => 'Agosto',
    '09' => 'Setembro',
    '10' => 'Outubro',
    '11' => 'Novembro',
    '12' => 'Dezembro'
];

$statuses = [
    'Todos',
    'Aguardando Emissão',
    'Emitida',
    'Pendência na Vistoria',
    'Processo de Vistoria',
    'Pendência de Proposta',
    'Cancelado',
    'Efetivado',
    'Recusa por vistoria'
];

$searchSeguradora = isset($_GET['seguradora']) ? $_GET['seguradora'] : '';
$searchTipoSeguro = isset($_GET['tipo_seguro']) ? $_GET['tipo_seguro'] : '';

$sql = "SELECT * FROM clientes WHERE MONTH(inicio_vigencia) = '$month' AND YEAR(inicio_vigencia) = '$year'";

if ($searchName) {
    $searchName = $conn->real_escape_string($searchName);
    $sql .= " AND nome LIKE '%$searchName%'";
}

if ($searchCpf) {
    $searchCpf = $conn->real_escape_string($searchCpf);
    $sql .= " AND cpf LIKE '%$searchCpf%'";
}

if ($searchStatus && $searchStatus !== 'Todos') {
    $searchStatus = $conn->real_escape_string($searchStatus);
    $sql .= " AND status = '$searchStatus'";
}

if ($searchSeguradora && $searchSeguradora !== 'Todas') {
    $searchSeguradora = $conn->real_escape_string($searchSeguradora);
    $sql .= " AND seguradora = '$searchSeguradora'";
}

if ($searchTipoSeguro && $searchTipoSeguro !== 'Todos') {
    $searchTipoSeguro = $conn->real_escape_string($searchTipoSeguro);
    $sql .= " AND tipo_seguro = '$searchTipoSeguro'";
}

$result = $conn->query($sql);



$seguradoras = [
    'Todas',
    'Aliro Seguro',
    'Allianz Seguros',
    'Azul Seguros',
    'HDI Seguros',
    'Liberty Seguros',
    'MAPFRE',
    'Unimed Seguros',
    'Porto Seguro',
    'Sompo Auto',
    'Tokio Marine Seguros',
    'Zurich Brasil Seguros',
    'Sancor Seguros',
    'Suhai',
    'Mitsui',
    'Sura Seguros',
    'EZZE',
    'Capemisa',
    'AKAD',
    'AssistCard',
    'AXA',
    'Ituran',
    'Pottencial',
    'SulAmerica',
    'VitalCard',
    'Bradesco'
];


$tiposSeguro = [
    'Todos',
    'Seguro Auto',
    'Seguro Residencial',
    'Acidentes Pessoais',
    'Seguro Moto',
    'Seguro de Vida',
    'Seguro Empresarial',
    'Consórcio',
    'Seguro Transporte',
    'Seguro Saúde',
    'Seguro Dental',
    'Seguro Frota',
    'Seguro Agronegócio'
];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Clientes em <?php echo $months[$month]; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../CSS/meses.css"> <!-- Link para o CSS separado -->
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Clientes em <?php echo $months[$month]; ?> <?php echo $year; ?></h2>
        <form method="GET" action="clients_by_month.php" class="form-inline mb-3">
    <input type="hidden" name="month" value="<?php echo $month; ?>">
    <form method="GET" action="clients_by_month.php" class="form-inline mb-3">
    <input type="hidden" name="month" value="<?php echo $month; ?>">
    
    <!-- Seleção de Ano -->
    <select name="year" class="form-control mr-sm-2" onchange="this.form.submit()">
        <option value="">Selecione o ano</option>
        <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
            <option value="<?php echo $y; ?>" <?php if ($year == $y) echo 'selected'; ?>><?php echo $y; ?></option>
        <?php endfor; ?>
    </select>
    
    <!-- Campo de Pesquisa por Nome -->
    <input type="text" name="name" class="form-control mr-sm-2" placeholder="Pesquisar por Nome"
           value="<?php echo htmlspecialchars($searchName); ?>">
    
    <!-- Campo de Pesquisa por CPF -->
    <input type="text" name="cpf" class="form-control mr-sm-2" placeholder="Pesquisar por CPF"
           value="<?php echo htmlspecialchars($searchCpf); ?>">
    
    <!-- Seleção de Status -->
    <select name="status" class="form-control mr-sm-2" onchange="this.form.submit()">
        <option value="">Filtrar por status</option>
        <?php foreach ($statuses as $status): ?>
            <option value="<?php echo $status; ?>" <?php if ($searchStatus == $status) echo 'selected'; ?>>
                <?php echo $status; ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <!-- Seleção de Seguradora -->
    <select name="seguradora" class="form-control mr-sm-2" onchange="this.form.submit()">
        <option value="">Filtrar por seguradora</option>
        <?php foreach ($seguradoras as $seguradora): ?>
            <option value="<?php echo $seguradora; ?>" <?php if ($searchSeguradora == $seguradora) echo 'selected'; ?>>
                <?php echo $seguradora; ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <!-- Seleção de Tipo de Seguro -->
    <select name="tipo_seguro" class="form-control mr-sm-2" onchange="this.form.submit()">
        <option value="">Filtrar por tipo de seguro</option>
        <?php foreach ($tiposSeguro as $tipo): ?>
            <option value="<?php echo $tipo; ?>" <?php if ($searchTipoSeguro == $tipo) echo 'selected'; ?>>
                <?php echo $tipo; ?>
            </option>
        <?php endforeach; ?>
    </select>
    
    <button type="submit" class="btn btn-primary">Buscar</button>
</form>


        <a href="months.php" class="btn btn-secondary mb-3"><i class="fas fa-arrow-left"></i> Voltar para Meses</a>
        <a href="../PHP_ACTION/export.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>"
            class="btn btn-info mb-3"><i class="fas fa-file-excel"></i> Exportar para Excel</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th><i class="fas fa-calendar-day"></i> Início Vigência</th>
                    <th><i class="fas fa-file-alt"></i> Proposta</th>
                    <th><i class="fas fa-user"></i> Nome</th>
                    <th><i class="fas fa-id-card"></i> CPF</th>
                    <th><i class="fas fa-phone"></i> Celular</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-dollar-sign"></i> Prêmio Líquido</th>
                    <th><i class="fas fa-building"></i> Seguradora</th>
                    <th><i class="fas fa-shield-alt"></i> Tipo de Seguro</th>
                    <th><i class="fas fa-percent"></i> Comissão (%)</th>
                    <th><i class="fas fa-calculator"></i> Comissão Calculada</th>
                    <th><i class="fas fa-tachometer-alt"></i> Status</th>
                    <th><i class="fas fa-file-pdf"></i> Proposta PDF</th>
                    <th><i class="fas fa-cogs"></i> Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    // Convertendo a data para o formato brasileiro
                    $inicio_vigencia = new DateTime($row['inicio_vigencia']);
                    $inicio_vigencia_formatado = $inicio_vigencia->format('d/m/Y');
                    $comissao_calculada = $row['premio_liquido'] * ($row['comissao'] / 100);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($inicio_vigencia_formatado); ?></td>
                        <td><?php echo htmlspecialchars($row['apolice']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['premio_liquido']); ?></td>
                        <td><?php echo htmlspecialchars($row['seguradora']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo_seguro']); ?></td>
                        <td><?php echo htmlspecialchars($row['comissao']); ?></td>
                        <td><?php echo htmlspecialchars($comissao_calculada); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <?php if ($row['pdf_path']): ?>
                                <a href="<?php echo htmlspecialchars($row['pdf_path']); ?>" target="_blank"><i
                                        class="fas fa-eye"></i> Visualizar PDF</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo htmlspecialchars($row['id']); ?>"
                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                            <a href="../PHP_ACTION/delete.php?id=<?php echo htmlspecialchars($row['id']); ?>"
                                class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>