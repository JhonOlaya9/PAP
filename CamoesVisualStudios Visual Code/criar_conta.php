<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "CamoesVisualStudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash(trim($_POST['senha']), PASSWORD_DEFAULT);

    $sql_verifica = "SELECT id FROM usuarios WHERE nome = ?";
    $stmt_verifica = $conn->prepare($sql_verifica);
    $stmt_verifica->bind_param("s", $nome);
    $stmt_verifica->execute();
    $stmt_verifica->store_result();

    if ($stmt_verifica->num_rows > 0) {
        echo "Erro: Nome de usuário já existe.";
    } else {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            echo "Conta criada com sucesso!";
            header("Location: login.html");
            exit;
        } else {
            echo "Erro ao criar conta: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_verifica->close();
}

$conn->close();
?>
