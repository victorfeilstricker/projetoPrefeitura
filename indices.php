<?php
require 'config.php';

// Dados fictícios baseados no porte de São Leopoldo para preencher os gráficos da apresentação
$total_moradores = 240000;
$qtd_homens = 115200; // Aprox 48%
$qtd_mulheres = 124800; // Aprox 52%

$iptu_cota_unica = 45000; // Quantidade de pessoas que pagaram à vista
$iptu_parcelado = 28000;  // Quantidade de pessoas que parcelaram

$total_arrecadado = "85.450.000,00";
$verba_disponivel = "72.100.000,00";
$verba_negativa = "13.350.000,00"; // Inadimplência / Déficit
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Índices da Cidade - Prefeitura Municipal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

       <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>

    <div class="main-content">

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card card-custom p-3 d-flex flex-row align-items-center">
                    <div class="icon-box bg-primary me-3"><i class="fa-solid fa-users-city"></i></div>
                    <div>
                        <h6 class="text-muted mb-0">Total de Moradores</h6>
                        <h4 class="fw-bold mb-0"><?= number_format($total_moradores, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3 d-flex flex-row align-items-center">
                    <div class="icon-box bg-success me-3"><i class="fa-solid fa-sack-dollar"></i></div>
                    <div>
                        <h6 class="text-muted mb-0">Total Arrecadado</h6>
                        <h4 class="fw-bold mb-0">R$ <?= $total_arrecadado ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3 d-flex flex-row align-items-center">
                    <div class="icon-box bg-info me-3"><i class="fa-solid fa-vault"></i></div>
                    <div>
                        <h6 class="text-muted mb-0">Verba Disponível</h6>
                        <h4 class="fw-bold mb-0">R$ <?= $verba_disponivel ?></h4>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-custom p-3 d-flex flex-row align-items-center">
                    <div class="icon-box bg-danger me-3"><i class="fa-solid fa-arrow-trend-down"></i></div>
                    <div>
                        <h6 class="text-muted mb-0">Verba Negativa (Inadimp.)</h6>
                        <h4 class="fw-bold mb-0">R$ <?= $verba_negativa ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card card-custom p-4">
                    <h5 class="mb-4 text-center text-secondary">Distribuição Populacional por Gênero</h5>
                    <div style="position: relative; height:300px; width:100%; display:flex; justify-content:center;">
                        <canvas id="graficoGenero"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card card-custom p-4">
                    <h5 class="mb-4 text-center text-secondary">Modalidade de Pagamento do IPTU</h5>
                    <div style="position: relative; height:300px; width:100%; display:flex; justify-content:center;">
                        <canvas id="graficoPagamento"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Gráfico 1: Gênero (Pie Chart - Gráfico de Pizza)
        const ctxGenero = document.getElementById('graficoGenero').getContext('2d');
        new Chart(ctxGenero, {
            type: 'pie',
            data: {
                labels: ['Homens (<?= number_format($qtd_homens, 0, ',', '.') ?>)', 'Mulheres (<?= number_format($qtd_mulheres, 0, ',', '.') ?>)'],
                datasets: [{
                    data: [<?= $qtd_homens ?>, <?= $qtd_mulheres ?>],
                    backgroundColor: ['#36A2EB', '#FF6384'], // Cores Azul e Rosa
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        // Gráfico 2: Pagamentos (Doughnut Chart - Gráfico de Rosca)
        const ctxPagamento = document.getElementById('graficoPagamento').getContext('2d');
        new Chart(ctxPagamento, {
            type: 'doughnut',
            data: {
                labels: ['Cota Única (<?= number_format($iptu_cota_unica, 0, ',', '.') ?>)', 'Parcelado (<?= number_format($iptu_parcelado, 0, ',', '.') ?>)'],
                datasets: [{
                    data: [<?= $iptu_cota_unica ?>, <?= $iptu_parcelado ?>],
                    backgroundColor: ['#198754', '#ffc107'], // Cores Verde e Amarelo
                    borderWidth: 1
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    </script>

</body>
</html>