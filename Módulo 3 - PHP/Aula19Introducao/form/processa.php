<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário Enviado!</title>
    <link rel="stylesheet" href="stylephp.css">
</head>

<body>
    <main class="container">
        <h1>Formulário Enviado!</h1>

        <?php

        // Recebendo os dados do formulário
        $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
        $sobreNome = isset($_POST['sobreNome']) ? $_POST['sobreNome'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $senha = isset($_POST['senha']) ? $_POST['senha'] : '';

        // Processando os dados (neste caso, apenas exibindo-os)
        echo "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>";
        echo "<p><strong>Sobrenome:</strong> " . htmlspecialchars($sobreNome) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
        echo "<p><strong>Senha:</strong> " . (htmlspecialchars($senha)) . "</p>";
        ?>

        <a href="index.html">Voltar á Página</a>

    </main>

</body>

</html>


<!--Médodo alternativo de fazer o mesmo código acima

<?php

// POST = No corpo da requisição
// GET = Na URL
// REQUEST = Pode ser tanto POST quanto GET

// Recebendo os dados do formulário
$nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$mensagem = isset($_POST['mensagem']) ? $_POST['mensagem'] : '';

// Processando os dados (neste caso, apenas exibindo-os)
echo "<h1>Formulário Recebido</h1>";
echo "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>";
echo "<p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>";
echo "<p><strong>Mensagem:</strong> " . nl2br(htmlspecialchars($mensagem)) . "</p>";

?>