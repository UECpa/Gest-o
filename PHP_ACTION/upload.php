<?php
include '../db.php';

header('Content-Type: application/json');

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = date('Y'); // Podemos modificar isso se quisermos filtrar por ano também

$uploadDir = 'Backup/' . $year . '/' . $month . '/';

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$response = ['message' => ''];

if (!empty($_FILES['files']['name'][0])) {
    foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['files']['name'][$key]);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFilePath)) {
            $sql = "INSERT INTO clientes (pdf_path, inicio_vigencia) VALUES ('$targetFilePath', NOW())";
            if ($conn->query($sql) === TRUE) {
                $response['message'] .= "O arquivo $fileName foi carregado com sucesso e registrado no banco de dados.\n";
            } else {
                $response['message'] .= "Houve um erro ao registrar o arquivo $fileName no banco de dados.\n";
            }
        } else {
            $response['message'] .= "Houve um erro ao carregar o arquivo $fileName.\n";
        }
    }
} else {
    $response['message'] = 'Nenhum arquivo foi enviado.';
}

echo json_encode($response);
?>
