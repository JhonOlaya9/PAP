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

$sql_user = "SELECT nome, email, foto_perfil FROM usuarios WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $usuario_id);
$stmt_user->execute();
$stmt_user->bind_result($nome, $email, $foto_perfil);
$stmt_user->fetch();
$stmt_user->close();

$tem_foto = !is_null($foto_perfil);


$sql_servicos = "SELECT servico_nome, data_pedido FROM servicos_pedidos WHERE usuario_id = ?";
$stmt_servicos = $conn->prepare($sql_servicos);
$stmt_servicos->bind_param("i", $usuario_id);
$stmt_servicos->execute();
$result_servicos = $stmt_servicos->get_result();

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil - Camões Visual Studios</title>
    <link rel="stylesheet" href="Style2.css">
    <link rel="icon" href="favicon.jpg">
    <style>
        .foto-perfil { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
        .main-content { margin: 20px; }
        input[type="text"] { padding: 6px; font-size: 14px; margin-bottom: 10px; }
        button { padding: 8px 12px; font-size: 14px; cursor: pointer; }
        ul { list-style: disc; margin-left: 20px; }
    </style>
</head>
<body>

<div class="main-content">
    <h2>Bienvenido, <?php echo htmlspecialchars($nome); ?>!</h2>

    <!-- Foto de Perfil -->
    <section>
        <h3>Foto de Perfil</h3>
        <?php if ($tem_foto): ?>
            <img src="ver_foto_perfil.php?id=<?php echo $usuario_id; ?>" class="foto-perfil" alt="Foto de perfil">
        <?php else: ?>
            <p>No has subido una foto de perfil.</p>
        <?php endif; ?>

        <form action="upload_foto_perfil.php" method="post" enctype="multipart/form-data">
            <input type="file" name="foto" accept="image/*" required><br><br>
            <button type="submit">Actualizar Foto</button>
        </form>
    </section>

    
    <section>
        <h3>Editar Nombre</h3>
        <form action="editar_nome.php" method="post">
            <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br>
            <button type="submit">Guardar Nombre</button>
        </form>
    </section>

   
    <section>
        <h3>Servicios Pedidos</h3>
        <?php if ($result_servicos->num_rows > 0): ?>
            <ul>
                <?php while ($servico = $result_servicos->fetch_assoc()): ?>
                    <li>
                        <?php echo htmlspecialchars($servico['servico_nome']); ?>
                        (Pedido en: <?php echo date('d/m/Y H:i', strtotime($servico['data_pedido'])); ?>)
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No has solicitado ningún servicio aún.</p>
        <?php endif; ?>
        <?php $stmt_servicos->close(); ?>
    </section>

    <div style="margin-top:20px;">
        <a href="index.php">Volver a Inicio</a> | 
        <a href="logout.php">Cerrar Sesión</a>
    </div>
</div>

</body>
</html>

<?php $conn->close(); ?>
