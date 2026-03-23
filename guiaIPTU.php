<?php
require 'config.php';

// Verifica se o ID do imóvel foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<div style='padding: 20px;'><h3>Erro: Imóvel não selecionado.</h3><a href='consultarImoveis.php'>Voltar para a lista</a></div>");
}

$id_imovel = $_GET['id'];
$tipo_pagamento = isset($_GET['tipo']) ? $_GET['tipo'] : 'unica';

try {
    $sql = "SELECT i.inscricao_municipal, i.logradouro, i.numero, i.bairro, i.complemento, p.nome, p.cpf 
            FROM imoveis i JOIN pessoas p ON i.contribuinte_id = p.id WHERE i.inscricao_municipal = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id_imovel]);
    $imovel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$imovel) die("Imóvel não encontrado no banco de dados.");
} catch (PDOException $e) {
    die("Erro ao gerar guia: " . $e->getMessage());
}

// === REGRAS DE NEGÓCIO DO VALOR E JUROS ===
$valor_total = 1150.49;
$ano_exercicio = "2026";
$taxa_juros_mensal = 0.0174; // 1,74% ao mês

if ($tipo_pagamento === 'parcelado') {
    // Pega a quantidade de parcelas (mínimo 3, máximo 12)
    $qtd_guias = isset($_GET['parcelas']) ? (int)$_GET['parcelas'] : 12;
    if ($qtd_guias < 3) $qtd_guias = 3;
    if ($qtd_guias > 12) $qtd_guias = 12;

    // Calcula a porcentagem total de juros (Ex: 12x = 20,88%)
    $juros_total_perc = $taxa_juros_mensal * $qtd_guias;
    
    // Aplica o juro sobre o valor total
    $valor_com_juros = $valor_total * (1 + $juros_total_perc);
    
    // Divide o novo valor total pelo número de parcelas
    $valor_parcela = $valor_com_juros / $qtd_guias;
    
    $valor_formatado = number_format($valor_parcela, 2, ',', '.');
    $info_adicional = "C/ Juros de " . number_format($juros_total_perc * 100, 2, ',', '.') . "%";
} else {
    $qtd_guias = 1;
    $valor_formatado = number_format($valor_total, 2, ',', '.');
    $info_adicional = "Sem Juros";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guia de IPTU - Prefeitura Municipal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

   <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

        <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>
    <div class="main-content">

        <div class="mb-4 d-flex justify-content-center align-items-center opcoes-pagamento flex-wrap gap-3 p-3 bg-light border rounded">
            <a href="consultarImoveis.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
            
            <a href="guiaIPTU.php?id=<?= $id_imovel ?>&tipo=unica" class="btn btn-<?= $tipo_pagamento == 'unica' ? 'primary' : 'outline-primary' ?> fw-bold">
                <i class="fa-solid fa-money-bill-1"></i> Cota Única (Sem Juros)
            </a>

            <form action="guiaIPTU.php" method="GET" class="d-flex align-items-center bg-white border p-2 rounded">
                <input type="hidden" name="id" value="<?= $id_imovel ?>">
                <input type="hidden" name="tipo" value="parcelado">
                
                <span class="me-2 fw-bold text-dark"><i class="fa-solid fa-calculator"></i> Parcelar em:</span>
                <select name="parcelas" class="form-select form-select-sm me-2" style="width: 80px;">
                    <?php for($p = 3; $p <= 12; $p++): ?>
                        <option value="<?= $p ?>" <?= (isset($qtd_guias) && $qtd_guias == $p && $tipo_pagamento == 'parcelado') ? 'selected' : '' ?>><?= $p ?>x</option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="btn btn-sm btn-<?= $tipo_pagamento == 'parcelado' ? 'success' : 'outline-success' ?> fw-bold">Gerar Carnê</button>
            </form>

            <button onclick="window.print()" class="btn btn-dark"><i class="fa-solid fa-print"></i> Imprimir Guias</button>
        </div>

        <?php for ($i = 1; $i <= $qtd_guias; $i++): 
            
            if ($tipo_pagamento === 'parcelado') {
                $cota_nome = "Parcela " . str_pad($i, 2, '0', STR_PAD_LEFT) . "/" . str_pad($qtd_guias, 2, '0', STR_PAD_LEFT);
                $mes_calc = 3 + $i;
                $ano_venc = 2026;
                if ($mes_calc > 12) { $mes_calc -= 12; $ano_venc++; }
                $vencimento = "10/" . str_pad($mes_calc, 2, '0', STR_PAD_LEFT) . "/$ano_venc";
                $num_codigo = str_pad($i, 2, '0', STR_PAD_LEFT);
            } else {
                $cota_nome = "Cota Única";
                $vencimento = "10/04/2026";
                $num_codigo = "00";
            }
            
            $classe_quebra = ($i % 4 == 0) ? 'quebra-pagina' : '';
        ?>

        <div class="guia-card <?= $classe_quebra ?>">
            
            <div class="guia-header">
                <div><img src="img/logo.jpg" alt="Logo" style="width: 50px;" class="me-2"></div>
                <div class="text-center flex-grow-1">
                    <h2 class="guia-title">Prefeitura de São Leopoldo</h2>
                    <p class="mb-0 fw-bold" style="font-size: 0.85rem;">Secretaria Municipal da Fazenda</p>
                </div>
                <div class="text-end">
                    <h5 class="fw-bold mb-0">Exercício</h5>
                    <h3 class="fw-bold mb-0"><?= $ano_exercicio ?></h3>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="guia-info-box">
                        <span class="guia-label">Contribuinte / Endereço do Imóvel</span>
                        <span class="guia-value" style="font-size: 0.85rem;">
                            <?= htmlspecialchars($imovel['nome']) ?> - CPF: <?= htmlspecialchars($imovel['cpf']) ?><br>
                            <?= htmlspecialchars($imovel['logradouro']) ?>, nº <?= htmlspecialchars($imovel['numero']) ?> - <?= htmlspecialchars($imovel['bairro']) ?>
                        </span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="guia-info-box text-center">
                        <span class="guia-label">Inscrição Municipal</span>
                        <span class="guia-value text-primary fs-5"><?= str_pad($imovel['inscricao_municipal'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-4">
                    <div class="guia-info-box text-center bg-light">
                        <span class="guia-label">Modalidade</span>
                        <span class="guia-value text-primary"><?= $cota_nome ?></span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="guia-info-box text-center bg-light">
                        <span class="guia-label">Vencimento</span>
                        <span class="guia-value text-danger"><?= $vencimento ?></span>
                    </div>
                </div>
                <div class="col-4">
                    <div class="guia-info-box text-center bg-light border-dark">
                        <span class="guia-label">Valor da Parcela (R$)</span>
                        <span class="guia-value valor-destaque"><?= $valor_formatado ?></span>
                        <small class="d-block text-muted" style="font-size: 0.65rem;"><?= $info_adicional ?></small>
                    </div>
                </div>
            </div>

                <div class="text-center mt-5 mb-4 p-3 border rounded" style="background-color: #f8f9fa;">
                    <p class="mb-2" style="font-family: monospace; font-size: 1.1rem; font-weight: bold; letter-spacing: 1px;">
                        81610000001 6  12345678901 2  34567890123 4  56789012345 6
                    </p>
    
                    <img src="https://barcode.tec-it.com/barcode.ashx?data=816100000016123456789012345678901234567890123456&code=Code128&dpi=96&dataseparator=" 
                        alt="Código de Barras IPTU" 
                        style="max-width: 100%; height: 70px; object-fit: contain;">
                    <p class="text-muted mt-2 mb-0" style="font-size: 0.8rem;">Autenticação Mecânica / Ficha de Compensação</p>
                </div>
        </div>

        <?php endfor; ?>

    </div>

</body>
</html>