<?php
require 'config.php';

// MÁGICA: Cria a tabela de assistentes administrativos automaticamente se ela não existir
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL
    )");
    
    // Insere um usuário padrão (admin) com a senha (123456) apenas se a tabela estiver vazia
    $stmt = $pdo->query("SELECT COUNT(*) FROM usuarios");
    if ($stmt->fetchColumn() == 0) {
        $senha_hash = password_hash('123456', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO usuarios (nome, usuario, senha) VALUES ('Assistente Administrativo', 'admin', '$senha_hash')");
    }
} catch (PDOException $e) {}

$erro = '';

// Quando o assistente clica em "Entrar"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    // Procura o usuário no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
    $stmt->execute([':usuario' => $usuario]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Se achou o usuário e a senha está correta
    if ($user && password_verify($senha, $user['senha'])) {
        // Salva na memória (sessão) que ele passou pela catraca
        $_SESSION['logado'] = true;
        $_SESSION['nome_usuario'] = $user['nome'];
        
        // Manda para o painel principal
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "<div class='alert alert-danger text-center'>Usuário ou senha incorretos!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Prefeitura Municipal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #212529; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background-color: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); width: 100%; max-width: 400px; }
        .login-logo { width: 100px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center">
            <img src="img/logo.jpg" alt="Prefeitura" class="login-logo">
            <h4 class="fw-bold mb-1">Acesso Restrito</h4>
            <p class="text-muted mb-4">Módulo de Gestão de IPTU</p>
        </div>

        <?= $erro ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold">Usuário</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="usuario" class="form-control" placeholder="Digite seu usuário" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Senha</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 fw-bold py-2"><i class="fa-solid fa-right-to-bracket"></i> Entrar no Sistema</button>
        </form>
        
        <div class="text-center mt-4">
            <small class="text-muted">Prefeitura Municipal de São Leopoldo - RS</small>
        </div>
    </div>

</body>
</html>