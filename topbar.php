<div class="topbar">
    <h4 class="mb-0 text-dark">Gestão de Imóveis</h4>
    
    <div class="user-profile d-flex align-items-center">
        <span class="me-2">Bem-vindo, <strong><?= htmlspecialchars($_SESSION['nome_usuario'] ?? 'Assistente') ?></strong></span>
        <i class="fa-solid fa-circle-user fa-2x text-secondary align-middle me-3"></i>
        
        <a href="logout.php" class="btn btn-sm btn-outline-danger" title="Sair ou trocar de conta">
            <i class="fa-solid fa-arrow-right-arrow-left"></i> Trocar Usuário
        </a>
    </div>
</div>