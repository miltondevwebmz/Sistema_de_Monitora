<?php
// register.php
// Página de registro de usuários com suporte a código de acesso.

require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $telefone = trim($_POST['telefone'] ?? '');
    $localizacao = trim($_POST['localizacao'] ?? '');
    $tipoEscolhido = $_POST['tipo'] ?? 'comum';
    $codigoAcesso = trim($_POST['codigo'] ?? '');

    if ($nome && $email && $senha) {
        // verifica se email já existe
        $stmt = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $message = 'Este e-mail já está registado.';
        } else {
            // valida código de acesso se necessário
            $tipoFinal = 'comum';
            if (in_array($tipoEscolhido, ['posto', 'admin'])) {
                $tipoValido = verifyAccessCode($pdo, $codigoAcesso);
                if ($tipoValido === $tipoEscolhido) {
                    $tipoFinal = $tipoValido;
                }
            }

            // se o utilizador escolheu "comum", não precisa de código
            if ($tipoEscolhido === 'comum') {
                $tipoFinal = 'comum';
            }

            // insere usuário
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha, telefone, tipo, localizacao) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$nome, $email, $hash, $telefone, $tipoFinal, $localizacao]);

            $idNovo = $pdo->lastInsertId();

            // se for posto, cria também registo em postos
            if ($tipoFinal === 'posto') {
                $stmt = $pdo->prepare('INSERT INTO postos (id_usuario, nome, tipo, telefone, localizacao) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$idNovo, $nome, 'hospital', $telefone, $localizacao]);
                // por agora deixei fixo tipo= hospital, depois pode-se fazer select.
            }

            $message = 'Conta criada com sucesso!';
        }
    } else {
        $message = 'Preencha pelo menos nome, e-mail e senha.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
    <style>
        form{
            display: flex;
            flex-flow: column nowrap;
        }
    </style>
</head>
<body>
    <h2>Criar Conta</h2>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
       
        <label for="senha">Senha:</label>
        <input type="password" name="senha" id="senha" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone">

        <label for="localizacao">Localização:</label>
        <input type="text" name="localizacao" id="localizacao">

        <label for="tipo">Tipo de conta:</label>
        <select name="tipo" id="tipo">
            <option value="comum">Usuário Comum</option>
            <option value="posto">Posto</option>
            <option value="admin">Administrador</option>
        </select>

        <label for="codigo">Código de Acesso (se necessário):</label>
        <input type="text" name="codigo" id="codigo">

        <button type="submit">Registrar</button>
    </form>

    <p>Já tem conta? <a href="login.php">Entrar</a></p>
</body>
</html>