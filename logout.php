<?php
session_start(); // Inicia a sessão

// Destrói todas as variáveis de sessão
session_unset();

// Destroi a sessão
session_destroy();

// Redireciona o usuário para a página de login
header("Location: login.php");
exit();
?>