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
    $sql = "SELECT tipo, imagem FROM imagens_blob WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($tipo, $imagem);
    if ($stmt->fetch()) {
        header("Content-Type: $tipo");
        echo $imagem;
    } else {
        echo "Imagem não encontrada.";
    }
    $stmt->close();
} else {
    echo "ID inválido.";
}

$conn->close();
?>
