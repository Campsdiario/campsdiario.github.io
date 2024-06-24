<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $nick = $_POST['nick'];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($senha !== $confirma_senha) {
        die("As senhas não coincidem.");
    }

    // Hash da senha
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);

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

    // Atualiza o usuário no banco de dados
    $stmt = $pdo->prepare("UPDATE users SET nick = ?, senha = ? WHERE email = ?");
    $stmt->execute([$nick, $senha_hashed, $email]);

    // Remove o token usado
    $stmt = $pdo->prepare("DELETE FROM user_verification WHERE email = ?");
    $stmt->execute([$email]);

    echo "Cadastro completado com sucesso!";
}
?>
