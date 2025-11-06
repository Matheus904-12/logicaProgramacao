<?php

include '../conexao/conexao.php';

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Nota</title>
    <link rel="stylesheet" href="../estilos/styleVerificar.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="../index.html">Início</a></li>
                <li><a href="cadastro.php">Cadastrar Usuário</a></li>
                <li><a href="atualizarCadastro.php">Atualizar Usuário</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="containerSection">
            <form action="atualizarNota.php" method="POST">
                <select name="curso" id="curso" class="estilo">
                    <option value="ads">Análise e Desenvolvimento de Sistemas</option>
                    <option value="es">Engenharia de Software</option>
                    <option value="si">Sistemas da Informação</option>
                    <option value="cc">Ciências da Computação</option>
                </select>
                <input type="submit" value="Buscar">
            </form>
        </section>



        <section>
            <form action="atualizarNota.php" method="POST" id="form-nota">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Sobrenome</th>
                            <th>Nota da Atividade</th>
                            <th>Nota da Prova</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if (isset($_POST['curso'])) {
                            $curso = $_POST['curso'];

                            $sql = "SELECT * FROM usuarios WHERE curso = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s", $curso);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    // Exiba os dados do usuário aqui
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";

                                    echo "<td>" . htmlspecialchars($row['nome']) . "</td>";

                                    echo "<td>" . htmlspecialchars($row['sobrenome']) . "</td>";

                                    $notaAtividade = isset($row['nota_atividade']) ? $row['nota_atividade'] : 'N/A';
                                    echo "<td>" . htmlspecialchars($notaAtividade) . "</td>";

                                    $notaProva = isset($row['nota_prova']) ? $row['nota_prova'] : 'N/A';
                                    echo "<td>" . htmlspecialchars($notaProva) . "</td>";
                                    echo "<td>";

                                    // area de edição (inicialmente escondida)
                                    echo "<div id='edit-".$row['id']."' style='display:none;'>";
                                    echo "<input type='number' name='nota_atividade_".$row['id']."' min='0' max='10' step='0.01' value='".htmlspecialchars($notaAtividade)."' placeholder='Atividade'>";
                                    echo "<input type='number' name='nota_prova_".$row['id']."' min='0' max='10' step='0.01' value='".htmlspecialchars($notaProva)."' placeholder='Prova'>";
                                    echo "<button type='submit' name='salvar' value='".$row['id']."'>Salvar</button>";
                                    echo "<button type='button' onclick=\"document.getElementById('edit-".$row['id']."').style.display='none'\">Cancelar</button>";
                                    echo "</div>";
                                    
                                    // botão que abre o input de edição
                                    echo "<button type='button' onclick=\"document.getElementById('edit-".$row['id']."').style.display='block'\">Atualizar Nota</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<div class='erro'>Nenhum usuário encontrado para o curso selecionado.</div>";
                            }

                            $stmt->close();
                        }
                        $conn->close();

                        ?>
                    </tbody>
                </table>
            </form>
        </section>
    </main>
</body>

</html>