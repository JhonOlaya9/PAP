<?php
session_start();
$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "CamoesVisualStudios"; // Corrigido

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];

    $sql = "SELECT id, senha, rol FROM usuarios WHERE nome = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erro na preparação da query: " . $conn->error);
    }

    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($usuario_id, $hash_senha, $role);
        $stmt->fetch();

        if (password_verify($senha, $hash_senha)) {
            $_SESSION['usuario_id'] = $usuario_id;
            $_SESSION['nome'] = $nome;
            $_SESSION['role'] = $role;

            if ($role === 'admin') {
                header("Location: admin.php");
                exit;
            } else {
                header("Location: index.php");
                exit;
            }
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }

    $stmt->close();
}

$conn->close();
?>


