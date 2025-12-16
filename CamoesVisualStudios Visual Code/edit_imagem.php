<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit;
}

$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "camoesvisualstudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

if (!isset($_GET['id'])) {
    die("ID da imagem não especificado.");
}

$id = intval($_GET['id']);

// Buscar dados da imagem
$sql = "SELECT titulo FROM imagens_blob WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario_id);
$stmt->execute();
$stmt->bind_result($titulo_atual);
$stmt->fetch();
$stmt->close();

if (!$titulo_atual) {
    die("Imagem não encontrada ou não pertence ao utilizador.");
}

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $novo_titulo = trim($_POST['titulo']);

    if ($novo_titulo === "") {
        $erro = "O título não pode estar vazio.";
    } else {
        $sql_update = "UPDATE imagens_blob SET titulo = ? WHERE id = ? AND usuario_id = ?";
        $stmt_up = $conn->prepare($sql_update);
        $stmt_up->bind_param("sii", $novo_titulo, $id, $usuario_id);

        if ($stmt_up->execute()) {
            header("Location: admin.php?edit_sucesso=1");
            exit;
        } else {
            $erro = "Erro ao atualizar título.";
        }
        $stmt_up->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Título da Imagem</title>
    <link rel="stylesheet" href="Style2.css">
</head>
<body>

<section>
    <h2>Editar Título</h2>

    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>

    <form method="post">
        <label for="titulo">Novo título:</label>
        <input type="text" name="titulo" id="titulo" value="<?php echo htmlspecialchars($titulo_atual); ?>" required>

        <button type="submit">Guardar</button>
    </form>
</section>

</body>
</html>
