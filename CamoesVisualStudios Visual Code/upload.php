<?php
$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "camoesvisualstudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["imagem"])) {
    $titulo = $_POST["titulo"];
    $tipo = $_FILES["imagem"]["type"];
    $dados = file_get_contents($_FILES["imagem"]["tmp_name"]);

    $sql = "INSERT INTO imagens_blob (titulo, tipo, imagem) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação: " . $conn->error);
    }

    $stmt->bind_param("sss", $titulo, $tipo, $dados);
    $stmt->send_long_data(2, $dados); // envia o conteúdo binário
    $stmt->execute();

    echo "✅ Imagem enviada com sucesso!";
    $stmt->close();
}

$conn->close();
?>
