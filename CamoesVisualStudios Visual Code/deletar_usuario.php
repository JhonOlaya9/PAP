<?php
session_start();

// Proteção de acesso
if (!isset($_SESSION['usuario_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "camoesvisualstudios");
if ($conn->connect_error) {
    die("Erro na ligação");
}

// Verifica ID
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$usuario_id = intval($_GET['id']);

// ❌ Impede apagar a própria conta
if ($usuario_id === $_SESSION['usuario_id']) {
    header("Location: admin.php?erro=auto_delete");
    exit;
}

// Verifica se utilizador existe
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    $stmt->close();
    header("Location: admin.php?erro=nao_existe");
    exit;
}
$stmt->close();

// Apaga utilizador
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->close();

$conn->close();

header("Location: admin.php?msg=deletado");
exit;
