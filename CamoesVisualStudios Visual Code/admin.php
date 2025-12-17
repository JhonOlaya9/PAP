<?php
session_start();
require 'admin_proteger.php';

// Conexão à BD
$conn = new mysqli("localhost", "root", "", "camoesvisualstudios");
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

// Buscar utilizadores
$result = $conn->query("SELECT id, nome, email, role FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="style2.css">
    <style>
        /* Centralizar todo o conteúdo da página */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Header empilhado e centralizado */
        header {
            text-align: center;
            margin: 40px 0 30px 0;
            max-width: 1200px;
            width: 100%;
        }

        header img.logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 15px;
        }

        header h1 {
            font-size: 2.5rem;
            margin: 0 0 15px 0;
            color: #000;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        nav a {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            border: 2px solid #000;
            color: #000;
            transition: background 0.25s ease, color 0.25s ease;
            display: inline-block;
        }

        nav a:hover {
            background: #000;
            color: #fff;
        }

        /* Main centralizado */
        main {
            max-width: 1200px;
            width: 100%;
            text-align: center;
            margin-bottom: 40px;
            padding: 0 15px;
        }

        /* Mensagens */
        .sucesso, .erro {
            max-width: 500px;
            margin: 0 auto 20px auto;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
        }
        .sucesso {
            background: #d4edda;
            color: #155724;
        }
        .erro {
            background: #f8d7da;
            color: #721c24;
        }

        /* Container dos cards */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 25px;
            justify-items: center;
        }

        /* Card individual */
        .user-card {
            background: #fff;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 20px;
            width: 100%;
            max-width: 320px;
            box-sizing: border-box;
            transition: transform 0.25s, box-shadow 0.25s;
            text-align: center;
        }
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.2);
        }

        .user-card h3 {
            margin-bottom: 12px;
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
        }
        .user-card p {
            margin: 6px 0;
            font-size: 1rem;
            color: #333;
        }

        /* Botões */
        .user-card a {
            display: inline-block;
            margin: 6px 8px 0 8px;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            text-decoration: none;
            border: 2px solid #000;
            color: #000;
            transition: background 0.25s ease, color 0.25s ease;
        }
        .user-card a:hover {
            background: #000;
            color: #fff;
        }

        /* Responsividade */
        @media (max-width: 900px) {
            .cards-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 600px) {
            .cards-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<header>
    <img src="favicon.jpg" alt="Logo do Site" class="logo">
    <h1>Painel Administrativo</h1>
    <nav>
        <a href="index.php">Site</a>
        <a href="admin.php">Utilizadores</a>
        <a href="logout.php">Sair</a>
    </nav>
</header>

<main>
    <!-- Mensagens -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'editado'): ?>
        <p class="sucesso">✅ Utilizador atualizado.</p>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deletado'): ?>
        <p class="sucesso">✅ Utilizador removido.</p>
    <?php endif; ?>
    <?php if (isset($_GET['erro']) && $_GET['erro'] === 'auto_delete'): ?>
        <p class="erro">❌ Não podes apagar a tua própria conta.</p>
    <?php endif; ?>

    <!-- Cards de usuários -->
    <div class="cards-container">
        <?php while ($u = $result->fetch_assoc()): ?>
            <div class="user-card">
                <h3><?= htmlspecialchars($u['nome']) ?></h3>
                <p><strong>ID:</strong> <?= $u['id'] ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($u['email']) ?></p>
                <p><strong>Função:</strong> <?= htmlspecialchars($u['role']) ?></p>
                <div>
                    <a href="editar_usuario.php?id=<?= $u['id'] ?>">Editar</a>
                    <?php if ($u['id'] != $_SESSION['usuario_id']): ?>
                        <a href="deletar_usuario.php?id=<?= $u['id'] ?>"
                           onclick="return confirm('Tem certeza que deseja excluir este utilizador?');">Excluir</a>
                    <?php else: ?>
                        <span>(Você)</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

</body>
</html>

<?php $conn->close(); ?>
