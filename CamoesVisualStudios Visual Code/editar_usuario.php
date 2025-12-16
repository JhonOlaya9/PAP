<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "CamoesVisualStudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = intval($_POST['id']);
    $novo_nome = trim($_POST['nome']);
    $nova_senha = trim($_POST['senha']);
    $nova_role = $_POST['role'];

    // Atualiza nome e função
    $sql = "UPDATE usuarios SET nome = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $novo_nome, $nova_role, $usuario_id);
    $stmt->execute();
    $stmt->close();

    // Atualiza senha se fornecida
    if (!empty($nova_senha)) {
        $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql_senha = "UPDATE usuarios SET senha = ? WHERE id = ?";
        $stmt_senha = $conn->prepare($sql_senha);
        $stmt_senha->bind_param("si", $hash_senha, $usuario_id);
        $stmt_senha->execute();
        $stmt_senha->close();
    }

    $conn->close();
    header("Location: admin.php");
    exit;
}

// Obtém dados do usuário
if (isset($_GET['id'])) {
    $usuario_id = intval($_GET['id']);

    $sql = "SELECT id, nome, email, role FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $stmt->bind_result($id, $nome, $email, $role);
    $stmt->fetch();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <center>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="Style2.css">
</head>
<body>
    <h1>Perfil do Usuário <?php echo htmlspecialchars($nome); ?>!</h1>
    
    <div class="perfil">
        <p><strong>ID:</strong> <?php echo $id; ?></p>
        <p><strong>Nome:</strong> <?php echo htmlspecialchars($nome); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Função:</strong> <?php echo htmlspecialchars($role); ?></p>
    </div>

    <h2>Editar Usuário</h2>
    <form action="editar_usuario.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <label>Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>

        <label>Nova Senha:</label>
        <input type="password" name="senha" placeholder="Digite uma nova senha (opcional)">

        <label>Função:</label>
        <select name="role">
            <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>Usuário</option>
            <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
        </select>

        <button type="submit">Atualizar</button>
        </center>
    </form>
    <a href="admin.php">Voltar ao painel</a>
</body>
</html>
