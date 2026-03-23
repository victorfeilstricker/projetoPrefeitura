<?php
require 'config.php';

// Mude os dados aqui se quiser
$nome = 'Guilherme Admin';
$email = 'admin@prefeitura.rs.gov.br';
$senha = '123456'; 

// ATENÇÃO: Se o seu login.php usa password_hash, descomente a linha abaixo:
// $senha = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
$stmt = $pdo->prepare($sql);
$stmt->execute([':nome' => $nome, ':email' => $email, ':senha' => $senha]);

echo "Usuário criado com sucesso! Pode ir lá logar.";
?>