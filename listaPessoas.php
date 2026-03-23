<?php
require 'config.php';

// Verifica se veio alguma mensagem de sucesso via URL (ex: ao excluir)
$mensagem = '';
if (isset($_GET['msg']) && $_GET['msg'] == 'excluido') {
    $mensagem = "<div class='alert alert-success mt-3'><i class='fa-solid fa-check-circle'></i> Contribuinte excluído com sucesso!</div>";
}

try {
    $stmt = $pdo->query("SELECT * FROM pessoas ORDER BY nome ASC");
    $pessoas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao carregar os dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Pessoas - Prefeitura Municipal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

       <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>

        <?= $mensagem ?>

        <div class="card card-custom p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0"><i class="fa-solid fa-list text-primary me-2"></i> Pessoas Cadastradas</h5>
                <a href="cadastroPessoal.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Novo Contribuinte</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nome Completo</th>
                            <th>CPF</th>
                            <th>Data de Nasc.</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pessoas as $p): ?>
                            <tr>
                                <td><?= $p['id'] ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($p['nome']) ?></td>
                                <td><?= htmlspecialchars($p['cpf']) ?></td>
                                <td><?= date('d/m/Y', strtotime($p['data_nascimento'])) ?></td>
                                <td>
                                     <a href="guiaIPTU.php?id=<?= $imovel['inscricao_municipal'] ?>" class="btn btn-sm btn-success" title="Emitir Guia IPTU">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                     </a>

                                    <a href="editarPessoa.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fa-solid fa-pen-to-square"></i></a>
                                    
                                    <a href="excluirPessoa.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir? Isso também apagará os imóveis no nome desta pessoa!');"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>