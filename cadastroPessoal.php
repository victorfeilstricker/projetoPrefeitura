<?php
require 'config.php';

$mensagem = '';

// Se o formulário foi enviado, tenta salvar no banco
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $sql = "INSERT INTO pessoas (nome, data_nascimento, cpf, sexo, telefone, email) 
                VALUES (:nome, :data_nascimento, :cpf, :sexo, :telefone, :email)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':data_nascimento' => $_POST['data_nascimento'],
            ':cpf' => $_POST['cpf'],
            ':sexo' => $_POST['sexo'],
            ':telefone' => !empty($_POST['telefone']) ? $_POST['telefone'] : null, // Opcional
            ':email' => !empty($_POST['email']) ? $_POST['email'] : null // Opcional
        ]);

        $mensagem = "<div class='alert alert-success mt-3'><i class='fa-solid fa-check-circle'></i> Contribuinte cadastrado com sucesso!</div>";
    } catch (PDOException $e) {
        // Verifica se o erro é de CPF duplicado (código 23000 do MySQL)
        if ($e->getCode() == 23000) {
            $mensagem = "<div class='alert alert-warning mt-3'><i class='fa-solid fa-triangle-exclamation'></i> Erro: Este CPF já está cadastrado no sistema.</div>";
        } else {
            $mensagem = "<div class='alert alert-danger mt-3'>Erro ao cadastrar: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pessoas - Prefeitura Municipal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

        <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>

    <div class="main-content">

        <div class="card card-custom">
            <div class="card-header-custom">
                <i class="fa-solid fa-user-plus text-primary me-2"></i> Registrar Nova Pessoa
            </div>
            <div class="card-body p-4">
                
                <form action="cadastroPessoal.php" method="POST">
                    
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control" placeholder="Ex: Maria Oliveira" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="date" name="data_nascimento" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">CPF <span class="text-danger">*</span></label>
                            <input type="text" name="cpf" class="form-control" placeholder="000.000.000-00" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Sexo <span class="text-danger">*</span></label>
                            <select name="sexo" class="form-select" required>
                                <option value="">Selecione...</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-muted mt-3 mb-3 border-bottom pb-2">Informações de Contato (Opcional)</h6>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" placeholder="(00) 00000-0000">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" placeholder="exemplo@email.com">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-3">
                        <button type="reset" class="btn btn-light me-2">Limpar</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save me-1"></i> Salvar Contribuinte</button>
                    </div>

                </form>
                
                <?= $mensagem ?>
                
            </div>
        </div>

    </div>

</body>
</html>