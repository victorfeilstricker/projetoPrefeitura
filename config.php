<?php
// 1. Inicia a sessão (a memória do navegador para saber quem está logado)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'prefeitura_sl';
$username = 'root'; // <-- Tem que ser root (Dono do banco de dados)
$password = '123456';// <-- A senha do seu MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}

// 3. A "Catraca" de Segurança
// Descobre em qual página o usuário está tentando entrar
$pagina_atual = basename($_SERVER['PHP_SELF']);

// Páginas que podem ser acessadas sem login
$paginas_livres = ['login.php', 'logout.php'];

// Se a página não é livre e o usuário NÃO está logado, manda ele de volta pro login!
if (!in_array($pagina_atual, $paginas_livres) && !isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}
?>