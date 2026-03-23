<?php
require 'config.php';

$mensagem = '';

// Se o formulário foi enviado, tenta salvar o imóvel no banco
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // AQUI ESTÁ A MÁGICA 1: O comando SQL agora inclui o campo 'cep'
        $sql = "INSERT INTO imoveis (logradouro, numero, bairro, cep, complemento, contribuinte_id) 
                VALUES (:logradouro, :numero, :bairro, :cep, :complemento, :contribuinte_id)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':logradouro' => $_POST['logradouro'],
            ':numero' => $_POST['numero'],
            ':bairro' => $_POST['bairro'],
            // AQUI ESTÁ A MÁGICA 2: Pegando o CEP digitado (se estiver vazio, salva como nulo)
            ':cep' => !empty($_POST['cep']) ? $_POST['cep'] : null,
            ':complemento' => !empty($_POST['complemento']) ? $_POST['complemento'] : null,
            ':contribuinte_id' => $_POST['contribuinte_id']
        ]);

        $mensagem = "<div class='alert alert-success mt-3'><i class='fa-solid fa-check-circle'></i> Imóvel cadastrado com sucesso!</div>";
    } catch (PDOException $e) {
        $mensagem = "<div class='alert alert-danger mt-3'>Erro ao cadastrar: " . $e->getMessage() . "</div>";
    }
}

// Busca todas as pessoas cadastradas no banco para preencher a lista suspensa
$stmt_pessoas = $pdo->query("SELECT id, nome, cpf FROM pessoas ORDER BY nome ASC");
$pessoas = $stmt_pessoas->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema IPTU - Prefeitura Municipal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>

        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="fa-solid fa-house-medical text-primary me-2"></i> Registrar Novo Imóvel
            </div>
            <div class="card-body p-4">
                
                <form action="index.php" method="POST">
                    
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Proprietário (Contribuinte) <span class="text-danger">*</span></label>
                            <select name="contribuinte_id" class="form-select border-primary" required>
                                <option value="">Selecione o proprietário na lista...</option>
                                <?php foreach ($pessoas as $pessoa): ?>
                                    <option value="<?= $pessoa['id'] ?>">
                                        <?= htmlspecialchars($pessoa['nome']) ?> (CPF: <?= htmlspecialchars($pessoa['cpf']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text"><i class="fa-solid fa-circle-info"></i> O proprietário precisa estar cadastrado primeiro no módulo de Pessoas.</div>
                        </div>
                    </div>

                    <h6 class="text-muted mb-3 border-bottom pb-2">Informações de Localização</h6>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Logradouro (Rua/Av) <span class="text-danger">*</span></label>
                            <input type="text" name="logradouro" class="form-control" placeholder="Ex: Av. João Corrêa" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Número <span class="text-danger">*</span></label>
                            <input type="text" name="numero" class="form-control" placeholder="Ex: 1234" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label">Bairro <span class="text-danger">*</span></label>
                            <input type="text" name="bairro" class="form-control" placeholder="Ex: Centro" required>
                        </div>
                        
                        <div class="col-md-4 mb-4">
                            <label class="form-label">CEP</label>
                            <input type="text" name="cep" class="form-control" placeholder="Ex: 93000-000">
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label">Complemento</label>
                            <input type="text" name="complemento" class="form-control" placeholder="Ex: Apto 404">
                            <div class="form-text">Opcional.</div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-3">
                        <button type="reset" class="btn btn-light me-2">Limpar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Salvar Imóvel</button>
                    </div>

                </form>
                
                <?= $mensagem ?>
                
            </div>
        </div>

    </div>

</body>
</html>