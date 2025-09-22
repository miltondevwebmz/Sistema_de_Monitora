<?php
// config.php
session_start();

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'emergencia');
define('DB_USER', 'root');
define('DB_PASS', ''); // ajusta conforme teu ambiente

try {
    $pdo = new PDO(
        'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Erro na conexão: ' . $e->getMessage());
}

function verifyAccessCode(PDO $pdo, string $code)
{
    if (empty($code)) return false;
    $stmt = $pdo->prepare('SELECT tipo FROM codigos_acesso WHERE codigo = ? AND ativo = 1 LIMIT 1');
    $stmt->execute([$code]);
    $tipo = $stmt->fetchColumn();
    return $tipo ?: false;
}

function isLoggedIn(): bool { return isset($_SESSION['id_usuario']); }
function requireLogin(): void { if (!isLoggedIn()) { header('Location: login.php'); exit; } }
function requireRole(string $role): void { if (!isLoggedIn() || ($_SESSION['tipo'] ?? '') !== $role) { header('Location: login.php'); exit; } }
?>
