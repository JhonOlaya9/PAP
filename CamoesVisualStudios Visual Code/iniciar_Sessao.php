<?php
// Ativa exibição de erros para debug (remova em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
ob_start(); // Ativa buffer de saída para evitar erros de cabeçalho

$conn = new mysqli("localhost", "root", "", "camoesvisualstudios");
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome  = trim($_POST['nome']);
    $senha = $_POST['senha'];

    $sql = "SELECT id, senha, role FROM usuarios WHERE nome = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Erro na query");
    }

    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($usuario_id, $hash_senha, $role);
        $stmt->fetch();

        if (password_verify($senha, $hash_senha)) {

            $_SESSION['usuario_id'] = $usuario_id;
            $_SESSION['nome'] = $nome;
            $_SESSION['role'] = $role;

            // 🔁 REDIRECIONAMENTO POR FUNÇÃO
            if ($role === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: index.php");
            }
            exit;

        } else {
            header("Location: login.html?erro=senha");
            exit;
        }
    } else {
        header("Location: login.html?erro=usuario");
        exit;
    }


    $stmt->close();
}

$conn->close();
ob_end_flush(); // Envia o buffer de saída
