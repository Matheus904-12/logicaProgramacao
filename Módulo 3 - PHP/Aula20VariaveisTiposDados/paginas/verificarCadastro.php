<?php

include '../conexao/conexao.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST['email'];

        // Sanear/normalizar entradas básicas
        $email = trim($email);

        // Verifica se o email existe
        $checkSql = "SELECT COUNT(*) AS cnt FROM usuarios WHERE email = ?";
        $checkStmt = $conn->prepare($checkSql);
        if ($checkStmt === false) {
            echo "Erro no prepare (verificação): " . $conn->error;
            $conn->close();
            exit;
        }

        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->bind_result($cnt);
        $checkStmt->fetch();
        $checkStmt->close();

        $conn->close();
    }
} catch (Exception $e) {
    echo "Exceção capturada: " . $e->getMessage();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Usuário</title>
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
        <form action="verificarCadastro.php" method="POST">

            
            <input type="email" id="email" name="email" placeholder="Digite seu email" required>

            <input type="submit" value="Buscar">
        </form>
    </section>

    <section>
        <div id="resultado">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if ($cnt > 0) {
                    echo "<div class='mensagem sucesso'>O email <strong>" . htmlspecialchars($email) . "</strong> já está cadastrado.</div>";
                } else {
                    echo "<div class='mensagem erro'>O email <strong>" . htmlspecialchars($email) . "</strong> não está disponível.</div>";
                }
            }
            ?>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Sobrenome</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Reabrir conexão para buscar usuários
                include '../conexao/conexao.php';

                $sql = "SELECT id, nome, sobrenome, email FROM usuarios";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['sobrenome']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td><div id='btn-excluir' data-id='" . $row['id'] . "'>Excluir</div></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhum usuário cadastrado.</td></tr>";
                }

                $conn->close();
                ?>
    </section>
    </main>
</body>

</html>