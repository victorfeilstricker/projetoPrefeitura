<?php
require 'config.php';

// Verifica se a URL enviou o ID da pessoa que deve ser excluída
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        // Prepara e executa o comando de deletar (DELETE)
        $stmt = $pdo->prepare("DELETE FROM pessoas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        
        // Redireciona de volta para a lista com uma mensagem de sucesso
        header("Location: listaPessoas.php?msg=excluido");
        exit;
        
    } catch (PDOException $e) {
        die("Erro ao excluir: " . $e->getMessage());
    }
} else {
    // Se tentar acessar o arquivo direto sem passar um ID, volta pra lista
    header("Location: listaPessoas.php");
    exit;
}
?>