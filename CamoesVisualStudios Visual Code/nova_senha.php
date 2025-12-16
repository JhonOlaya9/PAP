<?php
$token = $_GET['token'];
?>

<h2>Definir Nova Palavra-Passe</h2>
 <link rel="stylesheet" href="Style2.css">
<form action="atualizar_senha.php" method="POST">
  <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
  <label>Nova senha:</label><br>
  <input type="password" name="nova_senha" required><br><br>
  <button type="submit">Atualizar</button>
</form>
