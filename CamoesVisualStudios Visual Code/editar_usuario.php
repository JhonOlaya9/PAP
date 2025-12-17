<?php
session_start();
if (!isset($_SESSION['usuario_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "", "camoesvisualstudios");
if ($conn->connect_error) {
    die("Erro na ligação");
}

// POST → atualizar
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = intval($_POST['id']);
    $nova_senha = trim($_POST['senha']);
    $nova_role = $_POST['role'];

    // Impede o admin de remover o próprio admin
    if ($usuario_id == $_SESSION['usuario_id']) {
        $nova_role = 'admin';
    }

    $stmt = $conn->prepare("UPDATE usuarios SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $nova_role, $usuario_id);
    $stmt->execute();
    $stmt->close();

    if (!empty($nova_senha)) {
        $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
        $stmt->bind_param("si", $hash, $usuario_id);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: admin.php?msg=atualizado");
    exit;
}

// GET → buscar utilizador
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit;
}

$usuario_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT id, nome, email, role FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin.php");
    exit;
}

$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 50px 20px;
        }

        .container {
            background: #fff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
            font-size: 1.8rem;
            color: #333;
        }

        .perfil p {
            margin: 8px 0;
            color: #555;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            text-align: left;
            font-weight: bold;
            color: #444;
        }

        input, select {
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            padding: 12px;
            background: #333;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.25s;
        }

        button:hover {
            background: #555;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            transition: color 0.25s;
        }

        a:hover {
            color: #000;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Perfil de <?= htmlspecialchars($user['nome']) ?></h1>

    <div class="perfil">
        <p><strong>ID:</strong> <?= $user['id'] ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Função:</strong> <?= htmlspecialchars($user['role']) ?></p>
    </div>

    <form method="POST">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <label>Nova Senha</label>
        <input type="password" name="senha" placeholder="Deixe em branco para não alterar">

        <label>Função</label>
        <select name="role">
            <option value="user" <?= $user['role']=='user'?'selected':'' ?>>Usuário</option>
            <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
        </select>

        <button type="submit">Salvar Alterações</button>
    </form>

    <a href="admin.php">← Voltar</a>
</div>

</body>
</html>
