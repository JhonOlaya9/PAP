<?php
$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "camoesvisualstudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];
    $nova_senha = password_hash($_POST["nova_senha"], PASSWORD_DEFAULT);

    $sql = "SELECT email FROM recuperacao_senha WHERE token = ? AND expira > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        $sql_update = "UPDATE usuarios SET senha = ? WHERE email = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ss", $nova_senha, $email);
        $stmt_update->execute();

        echo "✅ Palavra-passe atualizada com sucesso!";
    } else {
        echo "Token inválido ou expirado.";
    }

    $stmt->close();
}
$conn->close();
?>
