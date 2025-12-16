<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
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

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT nome FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($nome);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Camões Visual Studios</title>
    <link rel="icon" href="favicon.jpg" type="image/x-icon">

    <style>
        :root {
            --branco: #ffffff;
            --preto: #000000;
            --radius: 12px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            background-color: var(--branco);
            color: var(--preto);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 40px 10px;
        }

        .container {
            max-width: 480px;
            width: 100%;
            text-align: center;
        }

        .logo img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            border: 3px solid var(--preto);
            margin-bottom: 20px;
            /* Sin grayscale, la foto mantiene sus colores */
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .link-btn {
            display: block;
            background: var(--preto);
            color: var(--branco);
            padding: 15px;
            margin: 12px 0;
            border-radius: var(--radius);
            font-size: 18px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .link-btn:hover {
            background: var(--branco);
            color: var(--preto);
            transform: scale(1.03);
            border: 2px solid var(--preto);
        }

        .logout {
            margin-top: 30px;
            font-size: 14px;
        }

        .logout a {
            color: var(--preto);
            text-decoration: underline;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="favicon.jpg" alt="Logo">
    </div>

    <h1>Bem-vindo, <?php echo htmlspecialchars($nome); ?>!</h1>

    <a class="link-btn" href="perfil.php">Perfil</a>
    <a class="link-btn" href="sobre.php">Sobre</a>
    <a class="link-btn" href="contato.php">Contacto</a>

    <div class="logout">
        <a href="logout.php">Sair</a>
    </div>
</div>

</body>
</html>
