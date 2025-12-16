<?php
session_start();

// Verifica sessão e função
if (!isset($_SESSION['usuario_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit;
}

// Conexão à BD
$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "camoesvisualstudios"; // corrigido para o nome real

$conn = new mysqli($host, $usuario, $senha_bd, $bd);

if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

// Buscar todos os usuários
$sql = "SELECT id, nome, email, role FROM usuarios";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="Style2.css">
</head>

<body>

    <header>
        <h1>Painel de Administração</h1>

        <nav>
            <a href="index.php">Home</a>
            <a href="servicos.php">Serviços</a>
            <a href="sobre.php">Sobre</a>
            <a href="contato.php">Contato</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <section>
        <h2>Lista de Utilizadores</h2>

        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Função</th>
                <th>Ações</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?php echo $row['id']; ?>">Editar</a>
                    <a href="deletar_usuario.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

</body>
</html>

<?php
$conn->close();
?>


