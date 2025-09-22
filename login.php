<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['tipo'] = $user['tipo'];

        header('Location: index.php');
        exit;
    } else {
        $message = 'Credenciais inválidas.';
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="Bibliotecas/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="Bibliotecas/fontawesome-free-6.6.0-web/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background: linear-gradient(135deg, #6dd5ed 0%, #2193b0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(33,147,176,0.2);
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
        }
        .login-title {
            font-weight: 700;
            color: #2193b0;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            background: linear-gradient(90deg, #2193b0 0%, #6dd5ed 100%);
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }
        .icon-input {
            position: relative;
        }
        .icon-input i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #2193b0;
        }
        .icon-input input {
            padding-left: 2.2rem;
        }
        .login-links {
            margin-top: 1.5rem;
            text-align: center;
        }
        .login-links a {
            color: #2193b0;
            text-decoration: none;
            margin: 0 0.5rem;
        }
        .login-links a:hover {
            text-decoration: underline;
        }
        .alert-danger {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-title">
            <i class="fas fa-user-circle fa-2x"></i>
            <span>Login</span>
        </div>
        <?php if ($message): ?>
            <div class="alert alert-danger text-center" role="alert">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3 icon-input">
                <label for="email" class="form-label">Email</label>
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3 icon-input">
                <label for="senha" class="form-label">Senha</label>
                <i class="fas fa-lock"></i>
                <input type="password" name="senha" id="senha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-sign-in-alt"></i> Entrar
            </button>
        </form>
        <div class="login-links">
            <p>Ainda não tem conta? <a href="register.php"><i class="fas fa-user-plus"></i> Criar conta</a></p>
            <p><a href="senha.php"><i class="fas fa-key"></i> Esqueci minha senha</a></p>
        </div>
    </div>
    <script src="Bibliotecas/bootstrap-5.3.8-dist/js/bootstrap.min.js"></script>
    <script src="Bibliotecas/jquery.min.js"></script>
</body>
</html>