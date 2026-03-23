<?php
session_start();
session_destroy(); // Apaga a memória (desloga o assistente)

// Redireciona para o portal da prefeitura de São Leopoldo!
header("Location: https://www.google.com/");
exit;
?>