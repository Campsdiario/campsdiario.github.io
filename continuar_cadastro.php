<?php
// Conexão com o banco de dados
$dsn = 'mysql:host=localhost;dbname=alterados';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Conexão falhou: ' . $e->getMessage());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verifique se o token é válido
    $stmt = $pdo->prepare("SELECT email FROM user_verification WHERE token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $email = $user['email'];
    } else {
        die("Token inválido ou expirado.");
    }
} else {
    die("Token não fornecido.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete seu Cadastro</title>
</head>
<body>
    <h2>Complete seu Cadastro</h2>
    <form action="processar_cadastro.php" method="post">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <label for="nick">Nick:</label>
        <input type="text" id="nick" name="nick" required>
        <br><br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br><br>
        <label for="confirma_senha">Confirme a Senha:</label>
        <input type="password" id="confirma_senha" name="confirma_senha" required>
        <br><br>
        <input type="submit" value="Completar Cadastro">
    </form>
</body>
</html>
