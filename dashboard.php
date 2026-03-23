<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Prefeitura de São Leopoldo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>

    <?php include 'menu.php'; ?>

    <div class="main-content">
        
        <?php include 'topbar.php'; ?>

        <div class="card card-custom" style="margin-bottom: 0;">
            <div class="card-header-custom">
                <i class="fa-solid fa-map-location-dot text-primary"></i> Mapa do Município - São Leopoldo, RS
            </div>
    
            <div class="card-body p-0">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d110486.35339235948!2d-51.2323069!3d-29.77435215!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9519428b49e8979d%3A0x6b449b010c732230!2zU8OjbyBMZW9wb2xkbywgUlM!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr" 
                    style="width: 100%; height: calc(100vh - 200px); border: 0; display: block; border-radius: 0 0 10px 10px;" 
                    allowfullscreen="" 
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

    </div>

</body>
</html>