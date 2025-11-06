<?php

include '../conexao/conexao.php';
// Mensagem de feedback (sucesso/erro)
$message = '';
$email_check_done = false;

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 1) Exclusão: se veio delete_id, excluir o usuário
        if (isset($_POST['delete_id'])) {
            $delete_id = (int) $_POST['delete_id'];

            $delSql = "DELETE FROM usuarios WHERE id = ?";
            $delStmt = $conn->prepare($delSql);
            if ($delStmt === false) {
                $message = 'Erro no prepare (delete): ' . $conn->error;
            } else {
                $delStmt->bind_param('i', $delete_id);
                // Executa a exclusão
                if ($delStmt->execute()) {
                    $message = "Usuário (ID: $delete_id) excluído com sucesso.";
                } else {
                    $message = 'Erro ao excluir: ' . $delStmt->error;
                }
                // Fecha statement de exclusão
                $delStmt->close();
            }
        }

        // 2) Verificação de email: roda somente se houver campo email enviado
        if (isset($_POST['email']) && !empty(trim($_POST['email']))) {
            $email = trim($_POST['email']);
            $checkSql = "SELECT COUNT(*) AS cnt FROM usuarios WHERE email = ?";
            $checkStmt = $conn->prepare($checkSql);
            if ($checkStmt === false) {
                $message = "Erro no prepare (verificação): " . $conn->error;
            } else {
                $checkStmt->bind_param("s", $email);
                $checkStmt->execute();
                $checkStmt->bind_result($cnt);
                $checkStmt->fetch();
                $checkStmt->close();
                $email_check_done = true;
            }
        }

        $conn->close();
    }
} catch (Exception $e) {
    $message = "Exceção capturada: " . $e->getMessage();
    if ($conn) { $conn->close(); }
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
            // Mostra mensagem de operação (ex.: exclusão) se existir
            if (!empty($message)) {
                echo "<div class='mensagem aviso'>" . htmlspecialchars($message) . "</div>";
            }

            // Se foi feita verificação de email, mostra o resultado
            if ($email_check_done) {
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
                        echo "<td><form method='POST' onsubmit=\"return confirm('Confirma exclusão do usuário?');\"><input type='hidden' name='delete_id' value='" . $row['id'] . "'><button type='submit' class='btn-excluir'>Excluir</button></form></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhum usuário cadastrado.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </section>
    </main>
</body>

</html>