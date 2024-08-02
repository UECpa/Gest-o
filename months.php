<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clientes por Mês</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        function showSummary(month) {
            $.ajax({
                url: 'summary.php',
                method: 'GET',
                data: { month: month },
                success: function(data) {
                    $('#summaryModal .modal-body').html(data);
                    $('#summaryModal').modal('show');
                }
            });
        }
    </script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .card {
            border-radius: 15px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 18px;
            border-bottom: 1px solid #0056b3;
        }
        .card-body {
            padding: 15px;
        }
        .btn-primary,
        .btn-info {
            border-radius: 25px;
            font-size: 16px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-info {
            background-color: #17a2b8;
            border: none;
        }
        .btn-info:hover {
            background-color: #117a8b;
        }
        .modal-content {
            border-radius: 15px;
        }
        .modal-header {
            border-bottom: none;
        }
        .modal-footer {
            border-top: none;
        }
        .modal-title {
            font-size: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center"><i class="fas fa-calendar-alt"></i> Clientes por Mês</h2>
    <div class="row">
        <?php
        $months = [
            '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março', '04' => 'Abril',
            '05' => 'Maio', '06' => 'Junho', '07' => 'Julho', '08' => 'Agosto',
            '09' => 'Setembro', '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
        ];

        foreach ($months as $num => $name) {
            echo '<div class="col-md-4 mb-3">';
            echo '<div class="card">';
            echo '<div class="card-header"><i class="fas fa-calendar-day"></i> ' . $name . '</div>';
            echo '<div class="card-body">';
            echo '<a href="clients_by_month.php?month=' . $num . '" class="btn btn-primary btn-block mb-2"><i class="fas fa-calendar-day"></i> Ver Clientes</a>';
            echo '<button class="btn btn-info btn-block" onclick="showSummary(\'' . $num . '\')"><i class="fas fa-info-circle"></i> Mostrar Resumo</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="summaryModalLabel"><i class="fas fa-calendar-check"></i> Resumo do Mês</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Conteúdo do resumo será carregado aqui via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
