<?php
$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "camoesvisualstudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT tipo_foto, foto_perfil FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($tipo, $foto);
    if ($stmt->fetch()) {
        header("Content-Type: $tipo");
        echo $foto;
    } else {
        echo "Foto não encontrada.";
    }
    $stmt->close();
} else {
    echo "ID inválido.";
}

$conn->close();
?>