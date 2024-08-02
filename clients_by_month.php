<?php
include 'db.php';

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$sql = "SELECT * FROM clientes WHERE MONTH(inicio_vigencia) = '$month' AND YEAR(inicio_vigencia) = '$year'";
$result = $conn->query($sql);

$months = [
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
    '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
    '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clientes em <?php echo $months[$month]; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Clientes em <?php echo $months[$month]; ?> <?php echo $year; ?></h2>
    <form method="GET" action="clients_by_month.php" class="form-inline mb-3">
        <input type="hidden" name="month" value="<?php echo $month; ?>">
        <select name="year" class="form-control mr-sm-2" onchange="this.form.submit()">
            <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
                <option value="<?php echo $y; ?>" <?php if ($year == $y) echo 'selected'; ?>><?php echo $y; ?></option>
            <?php endfor; ?>
        </select>
    </form>
    <a href="months.php" class="btn btn-secondary mb-3">Voltar para Meses</a>
    <a href="export.php?month=<?php echo $month; ?>&year=<?php echo $year; ?>" class="btn btn-info mb-3">Exportar para Excel</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Início Vigência</th>
            <th>Apólice</th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Número</th>
            <th>Email</th>
            <th>Prêmio Líquido</th>
            <th>Comissão (%)</th>
            <th>Comissão Calculada</th>
            <th>Status</th>
            <th>Arquivo PDF</th>
            <th>Ações</th>
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
                <td><?php echo $row['status']; ?></td>
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
</body>
</html>
