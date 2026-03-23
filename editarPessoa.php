<?php
require 'config.php';

$mensagem = '';

// 1. ATUALIZAÇÃO (Quando o botão de Salvar Edição é clicado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "UPDATE pessoas SET nome = :nome, data_nascimento = :data_nascimento, cpf = :cpf, sexo = :sexo, telefone = :telefone, email = :email WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':data_nascimento' => $_POST['data_nascimento'],
            ':cpf' => $_POST['cpf'],
            ':sexo' => $_POST['sexo'],
            ':telefone' => !empty($_POST['telefone']) ? $_POST['telefone'] : null,
            ':email' => !empty($_POST['email']) ? $_POST['email'] : null,
            ':id' => $_POST['id']
        ]);

        $mensagem = "<div class='alert alert-success mt-3'><i class='fa-solid fa-check-circle'></i> Dados atualizados com sucesso! <a href='listaPessoas.php'>Voltar para a lista</a>.</div>";
    } catch (PDOException $e) {
        $mensagem = "<div class='alert alert-danger mt-3'>Erro ao atualizar: " . $e->getMessage() . "</div>";
    }
}

// 2. BUSCAR DADOS ATUAIS (Quando a página carrega)
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    die("ID da pessoa não informado.");
}

$id_pessoa = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$stmt = $pdo->prepare("SELECT * FROM pessoas WHERE id = :id");
$stmt->execute([':id' => $id_pessoa]);
$pessoa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pessoa) {
    die("Pessoa não encontrada no banco de dados.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Pessoa - Prefeitura Municipal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; overflow-x: hidden; }
        .main-content { padding: 40px; }
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <div class="main-content container mt-5">
        <a href="listaPessoas.php" class="btn btn-secondary mb-3"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
        
        <div class="card card-custom p-4">
            <h4 class="mb-4 text-primary"><i class="fa-solid fa-user-pen"></i> Editar Contribuinte</h4>
            
            <form action="editarPessoa.php" method="POST">
                <input type="hidden" name="id" value="<?= $pessoa['id'] ?>">

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($pessoa['nome']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control" value="<?= $pessoa['data_nascimento'] ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">CPF</label>
                        <input type="text" name="cpf" class="form-control" value="<?= htmlspecialchars($pessoa['cpf']) ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Sexo</label>
                        <select name="sexo" class="form-select" required>
                            <option value="M" <?= $pessoa['sexo'] == 'M' ? 'selected' : '' ?>>Masculino</option>
                            <option value="F" <?= $pessoa['sexo'] == 'F' ? 'selected' : '' ?>>Feminino</option>
                            <option value="Outro" <?= $pessoa['sexo'] == 'Outro' ? 'selected' : '' ?>>Outro</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Telefone</label>
                        <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($pessoa['telefone']) ?>">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($pessoa['email']) ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save"></i> Atualizar Dados</button>
            </form>
            
            <?= $mensagem ?>
        </div>
    </div>
</body>
</html>