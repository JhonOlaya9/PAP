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
    $email = $_POST["email"];

    // Verifica se o email existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Salva o token
        $sql_token = "INSERT INTO recuperacao_senha (email, token, expira) VALUES (?, ?, ?)";
        $stmt_token = $conn->prepare($sql_token);
        $stmt_token->bind_param("sss", $email, $token, $expira);
        $stmt_token->execute();

        $link = "http://localhost/CamoesVisualStudios/nova_senha.php?token=$token";

        // Aqui você pode usar PHPMailer ou mail()
        echo "Link de recuperação: <a href='$link'>$link</a>";
    } else {
        echo "Email não encontrado.";
    }

    $stmt->close();
}
$conn->close();
?>
