<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introdução</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>
        <?php
        echo "Olá, Mundo!";
        ?>
    </h1>

    <h2>Váriaveis em PHP</h2>

    <p>
        <?php
        $nome = "João";
        $idade = 25;
        $altura = 1.75;
        $isEstudante = true;

        echo "Nome: " . $nome . "<br>";
        echo "Idade: " . $idade . "<br>";
        echo "Altura: " . $altura . "m<br>";
        echo "É estudante? " . ($isEstudante ? "Sim" : "Não") . "<br>";
        ?>
    </p>

    <br>

    <h2>Constantes em PHP</h2>

    <p>
        <?php

            const faculdade = "UMC";
            const cidade = "Mogi das Cruzes";

            echo "Faculdade: " . faculdade . "<br>";
            echo "Cidade: " . cidade . "<br>";

        ?>
    </p>

</body>
</html>