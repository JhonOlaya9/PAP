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
<img src="favicon.jpg" alt="Logo">
</div>
 
  <div class="linktree-titulo">Vamos Marcar o trabalho?</div>
 
  <div class="linktree-texto">

    Para nos contactar, preencha o formulário abaixo.<br>
<br>

    Pedimos que seja o mais específico possível para que possamos fornecer toda a informação necessária.<br><br>
 
    Normalmente respondemos entre .h e .h. Verifique a pasta de spam caso não receba resposta.<br><br>
 
    Se preferem envie um email para <strong>info@camoesvisualstudios.com</strong> ou contacte-nos via Instagram.
</div>
 
  <form action="form-handler.php" method="POST">
<input type="text" name="nome" placeholder="Nome *" required>
<input type="email" name="email" placeholder="Email *" required>
<input type="tel" name="telefone" placeholder="Telemóvel ">
 
    <div style="text-align:left; margin-top:10px;">
<label><input type="radio" name="tipo_evento" value="Casamento" required> Casamento</label><br>
<label><input type="radio" name="tipo_evento" value="Outro"> Outro</label>
</div>
 
    <input type="date" name="data" required>
<input type="text" name="local" placeholder="Onde? *" required>
<input type="time" name="hora" required>
<input type="text" name="descricao" placeholder="Descreva o evento  *" required>
<textarea name="mensagem" placeholder="Mensagem *" required></textarea>
 
    <label style="display:block; text-align:left; margin-top:10px;">
<input type="checkbox" name="consentimento" required>

      Consentimos a recolha dos nossos dados pessoais por Camões Visual Studios
</label>
 
    <button type="submit">Enviar</button>
</form>
 
  <a class="linktree-btn" href="index.php">Voltar</a>
 
</div>
 
</body>
</html>

 