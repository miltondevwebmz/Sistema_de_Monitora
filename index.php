<?php
require_once 'config.php';

// Verifica se o usuário está logado
requireLogin();

$tipo = $_SESSION['tipo'];
$nome = $_SESSION['nome'];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h2>Bem-vindo, <?= htmlspecialchars($nome) ?>!</h2>
    <p>Seu tipo de usuário: <?= htmlspecialchars($tipo) ?></p>

    <?php if ($tipo === 'comum'): ?>
        <h3>Painel de Usuário Comum</h3>
        <p>Aqui você pode registrar e visualizar suas ocorrências.</p>
    <?php elseif ($tipo === 'posto'): ?>
        <h3>Painel de Posto</h3>
        <p>Aqui você recebe alertas e pode atualizar ocorrências.</p>
    <?php elseif ($tipo === 'admin'): ?>
        <h3>Painel de Administrador</h3>
        <p>Aqui você tem controle total sobre o sistema.</p>

        <ul>
                <li><a href="gerir_usuarios.php">Ocorrencias Pendentes</a></li>
                <li><a href="relatorios.php">Relatorio de Ocorrencias</a></li>
                <li><a href="sistema.php">Decidir sobre o sistema</a></li>
            </ul> <br>

            <br><p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Possimus, temporibus iste blanditiis exercitationem sit cumque!</p><br>
            <div style="background-color: red; font-weight: bold; font: 2em;">
                <label for="nom">Nome:</label><br>
                <input type="text" name="nom" id="nom">
            </div>
    <?php endif; ?>

    <br>
    <a href="logout.php">Sair</a>
    <!-- <br> <br> <br>
    <ul>
        <li><a href="dashboard.php">Inicio</a></li>

        <?php if ($_SESSION['nivel_acesso'] === 'usuario'): ?>
            <li><a href="ocorrencias.php">Registar ocorrencias</a></li>
            <li><a href="perfil.php">Meu perfil</a></li>
        <?php endif; ?>

        <?php if ($_SESSION['nivel_acesso'] === 'posto'): ?>
            <li><a href="ocorrencias.php">Ocorrencias Pendentes</a></li>
            <li><a href="relatorios.php">Relatorio de Ocorrencias</a></li>
        <?php endif; ?>

        <?php if ($_SESSION['nivel_acesso'] === 'admin'): ?>
           
        <?php endif; ?>
    </ul> -->
</body>
</html>
