<?php

//Percorrer Array
$nomes = ["Ana", "Bruno", "Carla"];

foreach ($nomes as $nome) {
    echo $nome . "<br>";
}

//Percorrer Array Associativo
$notaAtividades = [
    "Ana" => 10,
    "Bruno" => 8,
    "Carla" => 9
];

foreach ($notaAtividades as $nome => $nota) {
    echo $nome . "- nota: " . $nota . "<br>";
}

//Percorrer Dois Arrays Simultaneamente
$notaProva = [
    "Caio" => 7,
    "Ana" => 6,
    "Bruno" => 8
];
$notaAtividades = [
    "Ana" => 10,
    "Bruno" => 8,
    "Carla" => 9
];


foreach ($notaAtividades as $nome => $nota) {
    $prova = $notaProva[$nome] ?? 0;

    echo $nome . " - nota atividades: " . $nota . " - nota prova: " . $prova . "<br>";
    echo $nome . " - nota prova: " . $prova . "<br>";
}

echo "<button type='submit'><a href='../index.html'>Voltar</a></button>";

?>