<?php
session_start();

// NÃO destrói sessão, não limpa cookies, nada mexe aqui.
// Apenas garante que o user está logado.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_POST['id'])) {
    die("Pedido inválido.");
}

$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "camoesvisualstudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

$id = intval($_POST['id']);
$usuario_id = $_SESSION['usuario_id'];

// Apagar imagem apenas se for do user
$sql = "DELETE FROM imagens_blob WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);

$stmt->execute();
$stmt->close();
$conn->close();

// Volta normalmente ao admin sem perder sessão
header("Location: admin.php?apagado=1");
exit;
?>
