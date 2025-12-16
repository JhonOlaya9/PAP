<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "camoesvisualstudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    $titulo = $_POST['titulo'] ?? 'Sem título';
    $tipo = $_FILES['imagem']['type'];
    $dados = file_get_contents($_FILES['imagem']['tmp_name']);

    $sql = "INSERT INTO imagens_blob (titulo, tipo, imagem, data_upload, usuario_id) VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssbi", $titulo, $tipo, $dados, $usuario_id);
    $stmt->send_long_data(2, $dados);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: perfil.php");
exit;
?>