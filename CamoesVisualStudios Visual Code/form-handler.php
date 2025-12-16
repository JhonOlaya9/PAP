<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$host = "localhost";
$usuario = "root";
$senha_bd = "";
$bd = "CamoesVisualStudios";

$conn = new mysqli($host, $usuario, $senha_bd, $bd);
if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = htmlspecialchars(trim($_POST["nome"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $mensagem = isset($_POST["mensagem"]) ? htmlspecialchars(trim($_POST["mensagem"])) : '';
    $tipo = isset($_POST["tipo"]) ? htmlspecialchars(trim($_POST["tipo"])) : '';
    $detalhes = isset($_POST["detalhes"]) ? htmlspecialchars(trim($_POST["detalhes"])) : '';

    if (empty($nome) || empty($email)) {
        echo "<p style='text-align:center;color:red;'>Nome e e-mail são obrigatórios!</p>";
        exit;
    }

    // Salvar mensagem no banco com data/hora
    $sql = "INSERT INTO mensagens (nome, email, mensagem, tipo, detalhes) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nome, $email, $mensagem, $tipo, $detalhes);
    
    if (!$stmt->execute()) {
        echo "<p style='text-align:center;color:red;'>Erro ao salvar mensagem no banco.</p>";
        exit;
    }

    // Configuração do PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'seuemail@gmail.com';
        $mail->Password = 'suasenha'; // Use uma senha de app do Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('seuemail@gmail.com', 'Creations Media');
        $mail->addAddress('papferreira08@gmail.com');

        $mail->Subject = "Novo contato do site Creations-Media";
        $mail->Body = "Nome: $nome\nEmail: $email\n\nMensagem:\n$mensagem\nTipo de Projeto: $tipo\nDetalhes:\n$detalhes";

        $mail->send();
        echo "<p style='text-align:center;'>Obrigado pelo contato, $nome! Sua mensagem foi registrada com sucesso.</p>";
    } catch (Exception $e) {
        echo "<p style='text-align:center;color:red;'>Erro ao enviar e-mail: {$mail->ErrorInfo}</p>";
    }
}

$conn->close();
?>
