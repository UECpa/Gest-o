<?php
include 'db.php';
include 'auth.php'; // Certifique-se de que auth.php gerencia a sessão e a autenticação

// Define o fuso horário para Brasília
date_default_timezone_set('America/Sao_Paulo');

// Número de registros por página
$registros_por_pagina = 50;

// Obter a página atual da URL, padrão é 1 se não for definido
$pagina_atual = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$pagina_atual = max(1, $pagina_atual); // Garantir que a página atual seja pelo menos 1

// Calcular o OFFSET para a consulta
$offset = ($pagina_atual - 1) * $registros_por_pagina;

// Inicialização das variáveis de busca
$search_nome = '';
$search_cpf = '';
$search_vigencia_de = '';
$search_vigencia_ate = '';

// Verifique se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Processar exclusão de notificações
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_notification'])) {
    $notificacao_id = $_POST['notificacao_id'];

    // Excluir a notificação
    $stmt = $conn->prepare("DELETE FROM notificacoes WHERE id = ?");
    $stmt->bind_param("i", $notificacao_id);
    $stmt->execute();
    $stmt->close();

    // Redirecionar para evitar resubmissão do formulário
    header('Location: index.php');
    exit();
}

// Obter todas as notificações
$stmt = $conn->prepare("SELECT * FROM notificacoes ORDER BY data_hora DESC");
$stmt->execute();
$notificacoes_result = $stmt->get_result();

// Saudação com base na hora do dia
$nome_usuario = isset($_SESSION['user_nome']) ? htmlspecialchars($_SESSION['user_nome']) : 'Usuário';
$hora_atual = (int) date('H');

if ($hora_atual >= 5 && $hora_atual < 12) {
    $saudacao = "Bom dia, $nome_usuario!";
} elseif ($hora_atual >= 12 && $hora_atual < 18) {
    $saudacao = "Boa tarde, $nome_usuario!";
} else {
    $saudacao = "Boa noite, $nome_usuario!";
}

// Processar busca se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_nome = $_POST['search_nome'] ?? '';
    $search_cpf = $_POST['search_cpf'] ?? '';
    $search_vigencia_de = $_POST['search_vigencia_de'] ?? '';
    $search_vigencia_ate = $_POST['search_vigencia_ate'] ?? '';
}

// Inicializar o array de parâmetros
$params = ["%$search_nome%", "%$search_cpf%"];

// Preparar a consulta com base nos filtros de busca
$sql = "SELECT * FROM clientes WHERE nome LIKE ? AND cpf LIKE ?";

if ($search_vigencia_de && $search_vigencia_ate) {
    $sql .= " AND inicio_vigencia BETWEEN ? AND ?";
    $params[] = $search_vigencia_de;
    $params[] = $search_vigencia_ate;
} elseif ($search_vigencia_de) {
    $sql .= " AND inicio_vigencia >= ?";
    $params[] = $search_vigencia_de;
} elseif ($search_vigencia_ate) {
    $sql .= " AND inicio_vigencia <= ?";
    $params[] = $search_vigencia_ate;
}

// Adicionar LIMIT e OFFSET
$sql .= " LIMIT ? OFFSET ?";
$params[] = $registros_por_pagina;
$params[] = $offset;

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params) - 2) . 'ii', ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Contar o total de registros
$count_sql = "SELECT COUNT(*) AS total FROM clientes WHERE nome LIKE ? AND cpf LIKE ?";
$count_params = ["%$search_nome%", "%$search_cpf%"];

if ($search_vigencia_de && $search_vigencia_ate) {
    $count_sql .= " AND inicio_vigencia BETWEEN ? AND ?";
    $count_params[] = $search_vigencia_de;
    $count_params[] = $search_vigencia_ate;
} elseif ($search_vigencia_de) {
    $count_sql .= " AND inicio_vigencia >= ?";
    $count_params[] = $search_vigencia_de;
} elseif ($search_vigencia_ate) {
    $count_sql .= " AND inicio_vigencia <= ?";
    $count_params[] = $search_vigencia_ate;
}

$count_stmt = $conn->prepare($count_sql);
$count_stmt->bind_param(str_repeat('s', count($count_params)), ...$count_params);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_registros = $count_result->fetch_assoc()['total'];

// Calcular o número total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// Função para formatar a data
function formatDate($date)
{
    return date('d/m/Y', strtotime($date));
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Clientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="IMG/logoM.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/index.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light custom-bg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shield-alt" style="font-size: 80px; color: white;"></i>
                <i class="fas fa-user" style="font-size: 45px; color: white;"></i>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="PHP_PAGES/months.php">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PHP_PAGES/info_loja.php">
                            <i class="fas fa-tachometer-alt"></i> Info MRG
                        </a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="PHP_ACTION/logout.php" class="nav-link">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="notificationDropdown" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <?php if ($notificacoes_result->num_rows > 0): ?>
                                <span class="badge badge-danger"><?php echo $notificacoes_result->num_rows; ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationDropdown">
                            <?php if ($notificacoes_result->num_rows > 0): ?>
                                <?php while ($notificacao = $notificacoes_result->fetch_assoc()): ?>
                                    <div class="dropdown-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-info-circle"></i>
                                            <?php echo htmlspecialchars($notificacao['mensagem']); ?>
                                            <small
                                                class="text-muted"><?php echo date('d/m/Y H:i', strtotime($notificacao['data_hora'])); ?></small>
                                        </div>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="notificacao_id"
                                                value="<?php echo $notificacao['id']; ?>">
                                            <button type="submit" name="delete_notification" class="btn btn-sm btn-danger ml-2">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <hr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <a class="dropdown-item" href="#">Sem notificações</a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Modal de Notificações -->
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">
                        <i class="bi bi-bell-fill"></i> Notificações
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($notificacoes_result->num_rows > 0): ?>
                        <?php while ($notificacao = $notificacoes_result->fetch_assoc()): ?>
                            <div class="notification-item d-flex align-items-start mb-3">
                                <div class="me-2">
                                    <i class="bi bi-info-circle-fill text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-1"><?php echo htmlspecialchars($notificacao['mensagem']); ?></p>
                                    <small
                                        class="text-muted"><?php echo date('d/m/Y H:i', strtotime($notificacao['data_hora'])); ?></small>
                                </div>
                                <form method="POST" class="ms-2" style="display:inline;">
                                    <input type="hidden" name="notificacao_id" value="<?php echo $notificacao['id']; ?>">
                                    <button type="submit" name="delete_notification" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            <hr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center">Sem notificações</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <p class="saudacao"><?php echo $saudacao; ?></p>
        <h2 class="text-center mb-4"><i class="fas fa-clipboard-list"></i> Gerenciamento de Clientes</h2>
        <form method="POST" action="index.php" class="mb-4">
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="search_nome"><i class="fas fa-user"></i> Nome</label>
                    <input type="text" class="form-control" id="search_nome" name="search_nome"
                        placeholder="Buscar por Nome" value="<?php echo htmlspecialchars($search_nome); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label for="search_cpf"><i class="fas fa-id-card"></i> CPF</label>
                    <input type="text" class="form-control" id="search_cpf" name="search_cpf"
                        placeholder="Buscar por CPF" value="<?php echo htmlspecialchars($search_cpf); ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="search_vigencia_de"><i class="fas fa-calendar-alt"></i> Vigência De</label>
                    <input type="date" class="form-control" id="search_vigencia_de" name="search_vigencia_de"
                        value="<?php echo htmlspecialchars($search_vigencia_de); ?>">
                </div>
                <div class="form-group col-md-2">
                    <label for="search_vigencia_ate"><i class="fas fa-calendar-alt"></i> Vigência Até</label>
                    <input type="date" class="form-control" id="search_vigencia_ate" name="search_vigencia_ate"
                        value="<?php echo htmlspecialchars($search_vigencia_ate); ?>">
                </div>
                <div class="form-group col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success"><i class="fas fa-search"></i> Buscar</button>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-12">
                    <a href="PHP_ACTION/export.php?search_nome=<?php echo urlencode($search_nome); ?>&search_cpf=<?php echo urlencode($search_cpf); ?>&search_vigencia_de=<?php echo urlencode($search_vigencia_de); ?>&search_vigencia_ate=<?php echo urlencode($search_vigencia_ate); ?>"
                        class="btn btn-info mr-2"><i class="fas fa-file-export"></i> Exportar dados específicos da
                        busca</a>
                    <a href="PHP_ACTION/export.php" class="btn btn-secondary"><i class="fas fa-file-export"></i>
                        Exportar Dados Lista Geral</a>
                </div>
            </div>
        </form>
        <a href="PHP_PAGES/add.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Adicionar Cliente</a>
        <h3>Lista Geral</h3>
        <table class="table table-striped table-bordered">
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
                    <tr>
                        <td><?php echo formatDate($row['inicio_vigencia']); ?></td>
                        <td><?php echo htmlspecialchars($row['apolice']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($row['numero']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['premio_liquido']); ?></td>
                        <td><?php echo htmlspecialchars($row['seguradora']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo_seguro']); ?></td>
                        <td><?php echo htmlspecialchars($row['comissao']); ?></td>
                        <td><?php echo htmlspecialchars($row['premio_liquido'] * ($row['comissao'] / 100)); ?></td>
                        <td>
                            <form method="POST" action="PHP_ACTION/update_status.php">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <select class="form-control" name="status" onchange="this.form.submit()">
                                    <option value="Aguardando Emissão" <?php if ($row['status'] == 'Aguardando Emissão')
                                        echo 'selected'; ?>>Aguardando Emissão</option>
                                    <option value="Emitida" <?php if ($row['status'] == 'Emitida')
                                        echo 'selected'; ?>>Emitida
                                    </option>
                                    <option value="Pendencia na vistoria" <?php if ($row['status'] == 'Pendencia na vistoria')
                                        echo 'selected'; ?>>Pendência na vistoria</option>
                                    <option value="Processo de Vistoria" <?php if ($row['status'] == 'Processo de Vistoria')
                                        echo 'selected'; ?>>Processo de Vistoria</option>
                                    <option value="Pendencia de Proposta" <?php if ($row['status'] == 'Pendencia de Proposta')
                                        echo 'selected'; ?>>Pendência de Proposta</option>
                                    <option value="Efetivado" <?php if ($row['status'] == 'Efetivado')
                                        echo 'selected'; ?>>
                                        Efetivado</option>
                                    <option value="Cancelado" <?php if ($row['status'] == 'Cancelado')
                                        echo 'selected'; ?>>
                                        Cancelado</option>
                                    <option value="Recusa por vistoria" <?php if ($row['status'] == 'Recusa por vistoria')
                                        echo 'selected'; ?>>Recusa por vistoria</option>
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
                        <td>
                            <?php if ($row['pdf_path']): ?>
                                <a href="uploads/<?php echo htmlspecialchars($row['pdf_path']); ?>" target="_blank">Visualizar
                                    PDF</a>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="PHP_PAGES/edit.php?id=<?php echo htmlspecialchars($row['id']); ?>"
                                class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Editar</a>
                            <a href="PHP_ACTION/delete.php?id=<?php echo htmlspecialchars($row['id']); ?>"
                                class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Deletar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <?php if ($pagina_atual > 1): ?>
            <a href="?pagina=<?php echo $pagina_atual - 1; ?>" class="btn btn-primary mx-2">
                <i class="fas fa-chevron-left"></i> Página Anterior
            </a>
        <?php endif; ?>
        <?php if ($pagina_atual < $total_paginas): ?>
            <a href="?pagina=<?php echo $pagina_atual + 1; ?>" class="btn btn-primary mx-2">
                Página Seguinte <i class="fas fa-chevron-right"></i>
            </a>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="JS/index.js"></script>
</body>

</html>