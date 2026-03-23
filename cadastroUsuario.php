<?php
require 'config.php';

$mensagem = '';

// Se o formulário foi enviado, tenta cadastrar o novo assistente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);

    if (!empty($nome) && !empty($usuario) && !empty($senha)) {
        try {
            // Verifica se o nome de usuário (login) já existe no banco
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario");
            $stmt_check->execute([':usuario' => $usuario]);
            
            if ($stmt_check->fetchColumn() > 0) {
                $mensagem = "<div class='alert alert-warning mt-3'><i class='fa-solid fa-triangle-exclamation'></i> Este nome de usuário já está em uso. Escolha outro.</div>";
            } else {
                // Criptografa a senha antes de salvar (Padrão de Segurança)
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO usuarios (nome, usuario, senha) VALUES (:nome, :usuario, :senha)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':usuario' => $usuario,
                    ':senha' => $senha_hash
                ]);
                $mensagem = "<div class='alert alert-success mt-3'><i class='fa-solid fa-check-circle'></i> Novo acesso criado com sucesso! O assistente já pode logar.</div>";
            }
        } catch (PDOException $e) {
            $mensagem = "<div class='alert alert-danger mt-3'>Erro ao cadastrar: " . $e->getMessage() . "</div>";
        }
    }
}

// Busca os usuários que já existem para mostrar na tabela
$stmt_users = $pdo->query("SELECT id, nome, usuario FROM usuarios ORDER BY nome ASC");
$usuarios_sistema = $stmt_users->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Acessos - Prefeitura Municipal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

       <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>

    <div class="main-content">

        <div class="row">
            <div class="col-md-5 mb-4">
                <div class="card card-custom p-4">
                    <h5 class="mb-4 text-primary"><i class="fa-solid fa-user-lock me-2"></i> Novo Assistente</h5>
                    
                    <form action="cadastroUsuario.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Maria Souza" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nome de Usuário (Login)</label>
                            <input type="text" name="usuario" class="form-control" placeholder="Ex: maria.souza" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Senha de Acesso</label>
                            <input type="password" name="senha" class="form-control" placeholder="Crie uma senha" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-save"></i> Criar Acesso</button>
                    </form>
                    
                    <?= $mensagem ?>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card card-custom p-4">
                    <h5 class="mb-4"><i class="fa-solid fa-list me-2"></i> Assistentes Cadastrados</h5>
                    
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Assistente</th>
                                    <th>Usuário (Login)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios_sistema as $u): ?>
                                    <tr>
                                        <td><?= $u['id'] ?></td>
                                        <td class="fw-bold"><?= htmlspecialchars($u['nome']) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($u['usuario']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>