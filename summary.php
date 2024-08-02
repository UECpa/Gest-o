<?php
include 'db.php';

$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = date('Y'); // Podemos modificar isso se quisermos filtrar por ano também

if ($month) {
    // Consulta para obter as seguradoras, tipos de seguro, prêmio líquido e comissão
    $sql = "SELECT seguradora, tipo_seguro, SUM(premio_liquido) as total_premio_liquido, SUM(premio_liquido * (comissao / 100)) as total_comissao, COUNT(*) as total_clientes FROM clientes WHERE MONTH(inicio_vigencia) = '$month' AND YEAR(inicio_vigencia) = '$year' GROUP BY seguradora, tipo_seguro";
    $result = $conn->query($sql);

    // Consulta para contar as apólices canceladas e efetivadas
    $status_sql = "SELECT status, COUNT(*) as total FROM clientes WHERE MONTH(inicio_vigencia) = '$month' AND YEAR(inicio_vigencia) = '$year' GROUP BY status";
    $status_result = $conn->query($status_sql);

    if ($result->num_rows > 0) {
        $total_premio_liquido_mes = 0;
        $total_comissao_mes = 0;
        $seguradoras = [];
        $tipos_seguro = [];
        $canceladas = 0;
        $efetivadas = 0;

        echo '<table class="table table-bordered">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>Seguradora</th>';
        echo '<th>Tipo de Seguro</th>';
        echo '<th>Total Prêmio Líquido</th>';
        echo '<th>Total Comissão</th>';
        echo '<th>Total Clientes</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['seguradora'] . '</td>';
            echo '<td>' . $row['tipo_seguro'] . '</td>';
            echo '<td>' . number_format($row['total_premio_liquido'], 2, ',', '.') . '</td>';
            echo '<td>' . number_format($row['total_comissao'], 2, ',', '.') . '</td>';
            echo '<td>' . $row['total_clientes'] . '</td>';
            echo '</tr>';

            $total_premio_liquido_mes += $row['total_premio_liquido'];
            $total_comissao_mes += $row['total_comissao'];
            $seguradoras[$row['seguradora']] = true;
            $tipos_seguro[$row['tipo_seguro']] = true;
        }

        echo '</tbody>';
        echo '</table>';

        // Contar as apólices canceladas e efetivadas
        while ($status_row = $status_result->fetch_assoc()) {
            if ($status_row['status'] == 'Cancelado') {
                $canceladas = $status_row['total'];
            } elseif ($status_row['status'] == 'Efetivado') {
                $efetivadas = $status_row['total'];
            }
        }

        echo '<p>Total Prêmio Líquido do Mês: R$ ' . number_format($total_premio_liquido_mes, 2, ',', '.') . '</p>';
        echo '<p>Total Comissão do Mês: R$ ' . number_format($total_comissao_mes, 2, ',', '.') . '</p>';
        echo '<p>Total de Seguradoras: ' . count($seguradoras) . '</p>';
        echo '<p>Total de Tipos de Seguro: ' . count($tipos_seguro) . '</p>';
        echo '<p>Total de Apólices Canceladas: ' . $canceladas . '</p>';
        echo '<p>Total de Apólices Efetivadas: ' . $efetivadas . '</p>';
    } else {
        echo '<p>Nenhum cliente encontrado para este mês.</p>';
    }
} else {
    echo '<p>Parâmetro de mês inválido.</p>';
}

$conn->close();
?>
