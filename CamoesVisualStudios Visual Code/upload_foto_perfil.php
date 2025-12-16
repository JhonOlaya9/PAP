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

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tipo = $_FILES['foto']['type'];
    $dados = file_get_contents($_FILES['foto']['tmp_name']);

    $sql = "UPDATE usuarios SET foto_perfil = ?, tipo_foto = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("bsi", $dados, $tipo, $usuario_id);
    $stmt->send_long_data(0, $dados);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: perfil.php");
exit;
?>
