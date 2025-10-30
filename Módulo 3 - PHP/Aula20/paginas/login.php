<?php
session_start();
// Verifica se o usuário já está logado
if (isset($_SESSION['usuario'])) {
    header('Location: index.html');
    exit();
}           
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../estilos/styleLogin.css">
</head>
<body>
    <header>
        <nav>
            <li><a href="../index.html">Início</a></li>
            <li><a href="#"></a>Atualizar Usuário</li>
            <li><a href="verificarUsuario.php"></a>Verificar Usuário</li>
        </nav>
    </header>
</body>
</html>