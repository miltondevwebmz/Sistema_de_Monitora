<?php
require_once 'config.php';

// Destrói todas as variáveis de sessão e encerra a sessão
session_unset();
session_destroy();

// Redireciona para a página de login
header('Location: login.php');
exit;
?>