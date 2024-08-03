<?php
include '../db.php';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="clientes.xls"');
header('Cache-Control: max-age=0');

$search_nome = isset($_GET['search_nome']) ? $_GET['search_nome'] : '';
$search_cpf = isset($_GET['search_cpf']) ? $_GET['search_cpf'] : '';
$search_vigencia_de = isset($_GET['search_vigencia_de']) ? $_GET['search_vigencia_de'] : '';
$search_vigencia_ate = isset($_GET['search_vigencia_ate']) ? $_GET['search_vigencia_ate'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

if ($month) {
    $sql = "SELECT * FROM clientes WHERE MONTH(inicio_vigencia) = '$month' AND YEAR(inicio_vigencia) = '$year'";
} elseif ($search_nome || $search_cpf || $search_vigencia_de || $search_vigencia_ate) {
    $sql = "SELECT * FROM clientes WHERE nome LIKE '%$search_nome%' AND cpf LIKE '%$search_cpf%'";
    
    if ($search_vigencia_de && $search_vigencia_ate) {
        $sql .= " AND inicio_vigencia BETWEEN '$search_vigencia_de' AND '$search_vigencia_ate'";
    } elseif ($search_vigencia_de) {
        $sql .= " AND inicio_vigencia >= '$search_vigencia_de'";
    } elseif ($search_vigencia_ate) {
        $sql .= " AND inicio_vigencia <= '$search_vigencia_ate'";
    }
} else {
    $sql = "SELECT * FROM clientes";
}

$result = $conn->query($sql);

echo '<table border="1">';
echo '<tr>';
echo '<th>Início Vigência</th>';
echo '<th>Apólice</th>';
echo '<th>Nome</th>';
echo '<th>CPF</th>';
echo '<th>Número</th>';
echo '<th>Email</th>';
echo '<th>Prêmio Líquido</th>';
echo '<th>Comissão (%)</th>';
echo '<th>Comissão Calculada</th>';
echo '<th>Status</th>';
echo '<th>Arquivo PDF</th>';
echo '</tr>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . $row['inicio_vigencia'] . '</td>';
    echo '<td>' . $row['apolice'] . '</td>';
    echo '<td>' . $row['nome'] . '</td>';
    echo '<td>' . $row['cpf'] . '</td>';
    echo '<td>' . $row['numero'] . '</td>';
    echo '<td>' . $row['email'] . '</td>';
    echo '<td>' . $row['premio_liquido'] . '</td>';
    echo '<td>' . $row['comissao'] . '</td>';
    echo '<td>' . $row['premio_liquido'] * ($row['comissao'] / 100) . '</td>';
    echo '<td>' . $row['status'] . '</td>';
    echo '<td>' . $row['pdf_path'] . '</td>';
    echo '</tr>';
}
echo '</table>';

$conn->close();
?>
