<?php
require 'config.php';

// MÁGICA: Verifica se a coluna CEP existe na tabela de imóveis. Se não existir, cria sozinha!
try {
    $colunas = $pdo->query("SHOW COLUMNS FROM imoveis LIKE 'cep'")->fetchAll();
    if (count($colunas) == 0) {
        $pdo->exec("ALTER TABLE imoveis ADD COLUMN cep VARCHAR(15) NULL AFTER complemento");
    }
} catch (PDOException $e) {}

// Variáveis para guardar o que o usuário digitou e qual filtro escolheu
$termo_busca = '';
$tipo_busca = 'endereco'; // Padrão é buscar por endereço

try {
    // Consulta base (traz tudo)
    $sql = "SELECT 
                i.inscricao_municipal, 
                i.logradouro, 
                i.numero, 
                i.bairro, 
                i.complemento,
                i.cep,
                p.nome AS nome_proprietario,
                p.cpf AS cpf_proprietario
            FROM imoveis i
            JOIN pessoas p ON i.contribuinte_id = p.id";

    // Se o usuário digitou algo na busca, adiciona o filtro WHERE dinâmico
    if (isset($_GET['busca']) && !empty(trim($_GET['busca']))) {
        $termo_busca = trim($_GET['busca']);
        $tipo_busca = $_GET['tipo_busca'] ?? 'endereco';

        // Decide qual coluna do banco de dados vai ser filtrada
        if ($tipo_busca === 'cpf') {
            $sql .= " WHERE p.cpf LIKE :busca";
        } elseif ($tipo_busca === 'cep') {
            $sql .= " WHERE i.cep LIKE :busca";
        } else {
            // Padrão: busca por endereço (logradouro)
            $sql .= " WHERE i.logradouro LIKE :busca";
        }
    }

    $sql .= " ORDER BY i.inscricao_municipal ASC";
            
    $stmt = $pdo->prepare($sql);
    
    // Passa o parâmetro com as porcentagens (%) para encontrar em qualquer parte do texto
    if (!empty($termo_busca)) {
        $stmt->execute([':busca' => '%' . $termo_busca . '%']);
    } else {
        $stmt->execute();
    }
    
    $imoveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erro ao carregar os dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Imóveis - Prefeitura Municipal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

       <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>

        <div class="card card-custom p-4">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fa-solid fa-city text-primary me-2"></i> Imóveis Cadastrados</h5>
                <a href="index.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Novo Imóvel</a>
            </div>

            <form action="consultarImoveis.php" method="GET" class="mb-4 bg-light p-3 border rounded">
                <label class="form-label fw-bold"><i class="fa-solid fa-filter text-secondary"></i> Consultar Registros por:</label>
                
                <div class="input-group">
                    <select name="tipo_busca" class="form-select" style="max-width: 250px;">
                        <option value="endereco" <?= $tipo_busca == 'endereco' ? 'selected' : '' ?>>Endereço (Logradouro)</option>
                        <option value="cpf" <?= $tipo_busca == 'cpf' ? 'selected' : '' ?>>CPF do Proprietário</option>
                        <option value="cep" <?= $tipo_busca == 'cep' ? 'selected' : '' ?>>CEP do Imóvel</option>
                    </select>
                    
                    <input type="text" name="busca" class="form-control" placeholder="Digite sua pesquisa..." value="<?= htmlspecialchars($termo_busca) ?>">
                    
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-search"></i> Pesquisar</button>
                    
                    <?php if (!empty($termo_busca)): ?>
                        <a href="consultarImoveis.php" class="btn btn-outline-secondary" title="Limpar Filtro"><i class="fa-solid fa-xmark"></i></a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Inscrição</th>
                            <th>Endereço Completo</th>
                            <th>Bairro / CEP</th>
                            <th>Proprietário</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($imoveis) > 0): ?>
                            <?php foreach ($imoveis as $imovel): ?>
                                <tr>
                                    <td class="fw-bold text-center"><?= str_pad($imovel['inscricao_municipal'], 6, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <?= htmlspecialchars($imovel['logradouro']) ?>, nº <?= htmlspecialchars($imovel['numero']) ?>
                                        <?php if (!empty($imovel['complemento'])): ?>
                                            - <span class="text-muted"><?= htmlspecialchars($imovel['complemento']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($imovel['bairro']) ?>
                                        <?php if (!empty($imovel['cep'])): ?>
                                            <br><small class="text-muted">CEP: <?= htmlspecialchars($imovel['cep']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary"><?= htmlspecialchars($imovel['nome_proprietario']) ?></div>
                                        <small class="text-muted">CPF: <?= htmlspecialchars($imovel['cpf_proprietario']) ?></small>
                                   <td>
                                        <a href="guiaIPTU.php?id=<?= $imovel['inscricao_municipal'] ?>" class="btn btn-sm btn-success" title="Emitir Guia IPTU">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                     </a>
    
                                        <a href="editarPessoa.php?id=<?= $imovel['inscricao_municipal'] ?>" class="btn btn-sm btn-warning" title="Editar">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                         </a>
    
                                        <a href="excluirPessoa.php?id=<?= $imovel['inscricao_municipal'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este registro? Não há como desfazer.');">
                                        <i class="fa-solid fa-trash"></i>
                                     </a>
                                </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhum registro encontrado para essa busca.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>