<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Contacto - Camões Visual Studios</title>
<link rel="stylesheet" href="Style2.css">
<link rel="icon" href="favicon.jpg">
</head>
<body>

<div class="linktree-container">

    <div class="linktree-logo">
        <img src="img/perfil.png" alt="Logo">
    </div>

    <div class="linktree-titulo">Contacte-nos</div>

    <form action="form-handler.php" method="POST">
        <input type="text" name="nome" placeholder="Seu nome" required>
        <input type="email" name="email" placeholder="Seu e-mail" required>
        <textarea name="mensagem" placeholder="Sua mensagem" required></textarea>
        <button type="submit">Enviar</button>
    </form>

    <a class="linktree-btn" href="index.php">Voltar</a>

</div>

</body>
</html>
