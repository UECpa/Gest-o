<?php
include 'db.php';
include 'auth.php';

$search_nome = '';
$search_cpf = '';
$search_vigencia_de = '';
$search_vigencia_ate = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_nome = $_POST['search_nome'];
    $search_cpf = $_POST['search_cpf'];
    $search_vigencia_de = $_POST['search_vigencia_de'];
    $search_vigencia_ate = $_POST['search_vigencia_ate'];
}

$sql = "SELECT * FROM clientes WHERE nome LIKE '%$search_nome%' AND cpf LIKE '%$search_cpf%'";

if ($search_vigencia_de && $search_vigencia_ate) {
    $sql .= " AND inicio_vigencia BETWEEN '$search_vigencia_de' AND '$search_vigencia_ate'";
} elseif ($search_vigencia_de) {
    $sql .= " AND inicio_vigencia >= '$search_vigencia_de'";
} elseif ($search_vigencia_ate) {
    $sql .= " AND inicio_vigencia <= '$search_vigencia_ate'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Clientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>/* Estilos gerais do corpo */

body {
    background-color: #f8f9fa;
}
.navbar {
    margin-bottom: 30px;
}
.navbar-brand img {
    max-height: 50px;
}
.form-inline input,
.form-inline button {
    margin-right: 10px;
}
.form-inline input {
    border-radius: 25px;
}
.btn-success,
.btn-info,
.btn-secondary,
.btn-primary {
    border-radius: 25px;
}
.table th,
.table td {
    text-align: center;
}
.table th {
    background-color: #343a40;
    color: white;
}
.table td {
    background-color: #ffffff;
}
.table-bordered {
    border: 1px solid #dee2e6;
}
.table-bordered td,
.table-bordered th {
    border: 1px solid #dee2e6;
}
.table a {
    color: #007bff;
}
.table a:hover {
    text-decoration: none;
    color: #0056b3;
}
.btn-sm {
    font-size: 0.875rem;
}
.btn-warning {
    border-radius: 25px;
}
.btn-danger {
    border-radius: 25px;
}
.animate__animated {
    animation-duration: 1s;
}
.animate__pulse {
    animation-name: pulse;
}
 </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand">
            <img src="IMG/LogoM.png" alt="MRG Corretora de Seguros">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link animate__animated animate__pulse" href="months.php">
                        <i class="fas fa-users"></i> Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link animate__animated animate__pulse"
                        href="https://mrgseguros.com.br/site/">
                        <i class="fas fa-tachometer-alt"></i> MRG site
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link animate__animated animate__pulse" href="contato.html">
                        <i class="fas fa-envelope"></i> Marketing
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
<div class="container mt-5">
    <h2 class="text-center"><i class="fas fa-clipboard-list"></i> Gerenciamento de Clientes</h2>
    <form method="POST" action="index.php" class="form-inline mb-3">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
            </div>
            <input class="form-control" type="search" placeholder="Buscar por Nome" name="search_nome" value="<?php echo $search_nome; ?>">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
            </div>
            <input class="form-control" type="search" placeholder="Buscar por CPF" name="search_cpf" value="<?php echo $search_cpf; ?>">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            </div>
            <input class="form-control" type="date" placeholder="Buscar por Data De" name="search_vigencia_de" value="<?php echo $search_vigencia_de; ?>">
        </div>
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
            </div>
            <input class="form-control" type="date" placeholder="Buscar por Data Até" name="search_vigencia_ate" value="<?php echo $search_vigencia_ate; ?>">
        </div>
        <button class="btn btn-success mb-2" type="submit"><i class="fas fa-search"></i> Buscar</button>
        <a href="export.php?search_nome=<?php echo $search_nome; ?>&search_cpf=<?php echo $search_cpf; ?>&search_vigencia_de=<?php echo $search_vigencia_de; ?>&search_vigencia_ate=<?php echo $search_vigencia_ate; ?>" class="btn btn-info mb-2 ml-2"><i class="fas fa-file-export"></i> Exportar dados específicos da busca</a>
        <a href="export.php" class="btn btn-secondary mb-2 ml-2"><i class="fas fa-file-export"></i> Exportar Dados Lista Geral</a>
    </form>
    <a href="add.php" class="btn btn-primary mb-3">Adicionar Cliente</a>
    <h2>Lista Geral</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th><i class="fas fa-calendar-day"></i> Início Vigência</th>
            <th><i class="fas fa-file-alt"></i>Proposta</th>
            <th><i class="fas fa-user"></i> Nome</th>
            <th><i class="fas fa-id-card"></i> CPF</th>
            <th><i class="fas fa-hashtag"></i> Celular</th>
            <th><i class="fas fa-envelope"></i> Email</th>
            <th><i class="fas fa-dollar-sign"></i> Prêmio Líquido</th>
            <th>Seguradora</th>
            <th>Tipo de Seguro</th>
            <th><i class="fas fa-percent"></i> Comissão (%)</th>
            <th><i class="fas fa-calculator"></i> Comissão Calculada</th>
            <th><i class="fas fa-tachometer-alt"></i> Status</th>
            <th><i class="fas fa-file-pdf"></i> Arquivo PDF</th>
            <th><i class="fas fa-cogs"></i> Ações</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['inicio_vigencia']; ?></td>
                <td><?php echo $row['apolice']; ?></td>
                <td><?php echo $row['nome']; ?></td>
                <td><?php echo $row['cpf']; ?></td>
                <td><?php echo $row['numero']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['premio_liquido']; ?></td>
                <td><?php echo $row['comissao']; ?></td>
                <td><?php echo $row['premio_liquido'] * ($row['comissao'] / 100); ?></td>
                <td>
                    <form method="POST" action="update_status.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <select class="form-control" name="status" onchange="this.form.submit()">
                            <option value="Efetivado" <?php if ($row['status'] == 'Efetivado') echo 'selected'; ?>>Efetivado</option>
                            <option value="Cancelado" <?php if ($row['status'] == 'Cancelado') echo 'selected'; ?>>Cancelado</option>
                            <option value="Recusa por vistoria" <?php if ($row['status'] == 'Recusa por vistoria') echo 'selected'; ?>>Recusa por vistoria</option>
                            <option value="Processo de Vistoria" <?php if ($row['status'] == 'Processo de Vistoria') echo 'selected'; ?>>Processo de Vistoria</option>
                        </select>
                    </form>
                    <?php
                    switch ($row['status']) {
                        case 'Efetivado':
                            echo '<i class="fas fa-check-circle text-success"></i>';
                            break;
                        case 'Cancelado':
                            echo '<i class="fas fa-times-circle text-danger"></i>';
                            break;
                        case 'Recusa por vistoria':
                            echo '<i class="fas fa-car-crash text-danger"></i>';
                            break;
                        case 'Processo de Vistoria':
                            echo '<i class="fas fa-car text-primary"></i>';
                            break;
                    }
                    ?>
                </td>
                <td><?php echo $row['seguradora']; ?></td>
                <td><?php echo $row['tipo_seguro']; ?></td>
                <td>
                    <?php if ($row['pdf_path']): ?>
                        <a href="<?php echo $row['pdf_path']; ?>" target="_blank">Visualizar PDF</a>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Deletar</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

