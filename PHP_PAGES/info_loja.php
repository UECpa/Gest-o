<?php
include '../db.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Adicionar nova seguradora
    if (isset($_POST['add_seguradora'])) {
        $nome = $_POST['nome'];
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];
        $numero_0800 = $_POST['numero_0800'];

        $stmt = $conn->prepare("INSERT INTO seguradoras (nome, usuario, senha, numero_0800) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $usuario, $senha, $numero_0800);
        $stmt->execute();
        $stmt->close();
    }

    // Atualizar informações da seguradora
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $usuario = $_POST['usuario'];
        $senha = $_POST['senha'];
        $numero_0800 = $_POST['numero_0800'];

        $stmt = $conn->prepare("UPDATE seguradoras SET usuario = ?, senha = ?, numero_0800 = ? WHERE id = ?");
        $stmt->bind_param("sssi", $usuario, $senha, $numero_0800, $id);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_POST['delete_seguradora'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM seguradoras WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    header('Location: info_loja.php');
    exit();
}

// Obter as informações das seguradoras
$result = $conn->query("SELECT * FROM seguradoras");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciais MRG</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../CSS/info.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Informações das Seguradoras</h2>
    <a href="../index.php" class="btn btn-secondary mb-4">
        <i class="bi bi-arrow-left"></i> Voltar
    </a>

    <!-- Botão para abrir o modal de adicionar seguradora -->
    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#addModal">
        <i class="bi bi-plus-circle"></i> Adicionar Seguradora
    </button>

    <!-- Modal para adicionar seguradora -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel"><i class="bi bi-plus-circle"></i> Adicionar Nova Seguradora</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="info_loja.php">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required>
                        </div>
                        <div class="form-group">
                            <label for="usuario">Usuário</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="text" class="form-control" id="senha" name="senha" required>
                        </div>
                        <div class="form-group">
                            <label for="numero_0800">Número 0800</label>
                            <input type="number" class="form-control" id="numero_0800" name="numero_0800" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Fechar</button>
                        <button type="submit" name="add_seguradora" class="btn btn-primary"><i class="bi bi-check-circle"></i> Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Usuário</th>
                <th>Senha</th>
                <th>Número 0800</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['nome']); ?></td>
            <td><?php echo htmlspecialchars($row['usuario']); ?></td>
            <td><?php echo htmlspecialchars($row['senha']); ?></td>
            <td><?php echo htmlspecialchars($row['numero_0800']); ?></td>
            <td>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editModal<?php echo $row['id']; ?>">
                    <i class="bi bi-pencil-square"></i> Editar
                </button>
                <!-- Botão de Exclusão -->
                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal<?php echo $row['id']; ?>">
                    <i class="bi bi-trash"></i> Excluir
                </button>
            </td>
        </tr>

        <!-- Modal de Exclusão -->
        <div class="modal fade" id="deleteModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel"><i class="bi bi-trash"></i> Confirmar Exclusão</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="info_loja.php">
                        <div class="modal-body">
                            <p>Você tem certeza que deseja excluir esta seguradora?</p>
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
                            <button type="submit" name="delete_seguradora" class="btn btn-danger"><i class="bi bi-trash"></i> Excluir</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de Edição -->
        <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-square"></i> Editar Informações</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="info_loja.php">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <div class="form-group">
                                <label for="usuario<?php echo $row['id']; ?>">Usuário</label>
                                <input type="text" class="form-control" id="usuario<?php echo $row['id']; ?>" name="usuario" value="<?php echo htmlspecialchars($row['usuario']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="senha<?php echo $row['id']; ?>">Senha</label>
                                <input type="text" class="form-control" id="senha<?php echo $row['id']; ?>" name="senha" value="<?php echo htmlspecialchars($row['senha']); ?>" placeholder="Deixe em branco para manter a senha atual">
                            </div>
                            <div class="form-group">
                                <label for="numero_0800<?php echo $row['id']; ?>">Número 0800</label>
                                <input type="number" class="form-control" id="numero_0800<?php echo $row['id']; ?>" name="numero_0800" value="<?php echo htmlspecialchars($row['numero_0800']); ?>" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="bi bi-x-circle"></i> Fechar</button>
                            <button type="submit" name="edit_seguradora" class="btn btn-primary"><i class="bi bi-check-circle"></i> Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
