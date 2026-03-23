<?php
session_start();
session_destroy(); // Apaga a memória de quem estava logado

// Manda de volta para a tela de login do nosso sistema
header("Location: login.php");
exit;
?>