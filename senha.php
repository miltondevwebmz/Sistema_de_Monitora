<?php
require_once 'config.php';

$message = '';
$step = 'solicitar'; // etapas: solicitar token / redefinir senha

// Passo 1: Solicitar token
if (isset($_POST['email_solicitar'])) {
    $email = trim($_POST['email_solicitar']);

    $stmt = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(4)); // token de 8 caracteres
        $expiracao = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        // Salvar token no banco
        $stmt = $pdo->prepare('INSERT INTO senha_tokens (id_usuario, token, expiracao) VALUES (?, ?, ?)');
        $stmt->execute([$user['id_usuario'], $token, $expiracao]);

        $message = "Token gerado com sucesso! Use este token para redefinir sua senha: <strong>$token</strong> (válido por 2 min)";
        $step = 'redefinir';
    } else {
        $message = 'Email não encontrado.';
    }
}

// Passo 2: Redefinir senha
if (isset($_POST['token']) && isset($_POST['nova_senha']) && isset($_POST['confirma_senha'])) {
    $token = trim($_POST['token']);
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    if ($nova_senha !== $confirma_senha) {
        $message = 'Senhas não coincidem.';
        $step = 'redefinir';
    } else {
        $stmt = $pdo->prepare('SELECT id_usuario, expiracao FROM senha_tokens WHERE token = ? LIMIT 1');
        $stmt->execute([$token]);
        $tokenInfo = $stmt->fetch();
    
        if ($tokenInfo && strtotime($tokenInfo['expiracao']) > time()) {
            $hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE usuarios SET senha = ? WHERE id_usuario = ?');
            $stmt->execute([$hash, $tokenInfo['id_usuario']]);

            // Remove token após uso
            $stmt = $pdo->prepare('DELETE FROM senha_tokens WHERE token = ?');
            $stmt->execute([$token]);

            $message = 'Senha alterada com sucesso! Agora pode fazer login.';
            $step = 'solicitar';
        } else {
            $message = 'Token inválido ou expirado.';
            $step = 'solicitar';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
</head>
<body>
    <h2>Recuperação de Senha</h2>
    <?php if ($message) echo "<p>$message</p>"; ?>

    <?php if ($step === 'solicitar'): ?>
    <form method="post">
        <label for="email_solicitar">Email:</label>
        <input type="email" name="email_solicitar" required>

        <button type="submit">Solicitar Token</button>
    </form>
    <?php elseif ($step === 'redefinir'): ?>
    <form method="post">
        <label for="token">Token:</label>
        <input type="text" name="token" id="token" required>

        <label for="nova_senha">Nova Senha:</label>
        <input type="password" name="nova_senha" id="nova_senha" required>

        <label for="confirma_senha">Confirmar Senha:</label>
        <input type="password" name="confirma_senha" id="confirma_senha" required>

        <button type="submit">Redefinir Senha</button>
    </form>
    <?php endif; ?>

    <p><a href="login.php">Voltar ao Login</a></p>
</body>
</html>