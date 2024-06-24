<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Inclui os arquivos do PHPMailer
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

// Conexão com o banco de dados (ajuste as credenciais conforme necessário)
$dsn = 'mysql:host=localhost;dbname=alterados';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Conexão falhou: ' . $e->getMessage());
}

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura o e-mail do formulário
    $email = $_POST['email'];

    // Gera um token único
    $token = bin2hex(random_bytes(16));

    // Armazena o token no banco de dados
    $stmt = $pdo->prepare("INSERT INTO user_verification (email, token) VALUES (?, ?)");
    $stmt->execute([$email, $token]);

    // Inicia o objeto PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Endereço do servidor SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'campsdiario4@gmail.com';  // Seu endereço de e-mail
        $mail->Password = 'Alt2024@@';  // Sua senha de e-mail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Ativa a criptografia TLS
        $mail->Port = 587;  // Porta TCP para conexão

        // Remetente e destinatário
        $mail->setFrom('campsdiario4@gmail.com', 'ALTERADOS');
        $mail->addAddress($email);  // E-mail do destinatário

        // Conteúdo do e-mail
        $mail->isHTML(true); // Define o formato do e-mail como HTML
        $mail->Subject = 'Confirmação de Cadastro';
        $mail->Body    = 'Olá! Seu cadastro foi realizado com sucesso. Clique no link abaixo para completar seu cadastro:<br>';
        $mail->Body   .= '<a href="https://campsdiario.github.io/alterados.github.io/continuar_cadastro.php?token=' . $token . '">Clique aqui para continuar o cadastro</a>';

        // Ativar saída de depuração detalhada
        $mail->SMTPDebug = 2;

        // Envia o e-mail
        $mail->send();
        echo 'E-mail enviado com sucesso!';
    } catch (Exception $e) {
        echo 'Erro ao enviar o e-mail: ' . $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Cadastro de Usuário</h2>
                <br>
                <form action="cadastro.php" method="post">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                    <br><br>
                    <input type="submit" value="Cadastrar" class="cta-button">
                </form>
            </div>
        </div>
    </section>
</body>
</html>
