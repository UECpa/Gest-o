<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apolice = $_POST['apolice'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS count FROM clientes WHERE apolice = ?");
    $stmt->bind_param('s', $apolice);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode(['exists' => $row['count'] > 0]);

    $stmt->close();
}
?>
