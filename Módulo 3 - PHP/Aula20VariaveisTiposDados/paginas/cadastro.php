<?php

include '../conexao/conexao.php';

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $email = $_POST['email'];
        $curso = $_POST['curso'];
        // Sanear/normalizar entradas básicas
        $nome = trim($nome);
        $sobrenome = trim($sobrenome);
        $email = trim($email);
        $curso = trim(string: $curso);

        // 1) Verifica se o email já existe
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

        if ($cnt > 0) {
            // Email já existe — mensagem amigável
            echo "<div class='mensagem erro'>O email <strong>" . htmlspecialchars($email) . "</strong> já está cadastrado.</div>";
            $conn->close();
            exit;
        }

        // 2) Inserir dados no banco de dados usando prepared statement (placeholders)
        $sql = "INSERT INTO usuarios (nome, sobrenome, email, curso) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            // Erro no prepare
            echo "Erro no prepare (insert): " . $conn->error;
        } else {
            // Vincula os parâmetros (4 strings)
            $stmt->bind_param("ssss", $nome, $sobrenome, $email, $curso);

            if ($stmt->execute()) {
                echo "<div class='mensagem sucesso'>Novo registro criado com sucesso</div>";
            } else {
                // Caso inesperado — mostrar mensagem mais amigável
                if (strpos($stmt->error, 'Duplicate') !== false || strpos($stmt->error, 'duplicate') !== false) {
                    echo "<div class='mensagem erro'>O email informado já existe.</div>";
                } else {
                    echo "Erro ao executar statement: " . $stmt->error;
                }
            }

            $stmt->close();
        }

        $conn->close();
    }
}

catch (Exception $e) {
    echo "Exceção capturada: " . $e->getMessage();
    $conn->close();
}



?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="../estilos/styleCadastrar.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="../index.html">Início</a></li>
                <li><a href="#">Atualizar Usuário</a></li>
                <li><a href="verificarCadastro.php">Verificar Usuário</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Cadastro de Usuário</h1>
        <form action="cadastro.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="sobrenome">Sobrenome:</label>
            <input type="text" id="sobrenome" name="sobrenome" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="curso">Selecione o Curso:</label>
            <select name="curso" id="curso">
                <option value="ads">Análise e Desenvolvimento de Sistemas</option>
                <option value="es">Engenharia de Software</option>
                <option value="si">Sistemas da Informação</option>
                <option value="cc">Ciência da Computação</option>
            </select>

            <input type="submit" value="Cadastrar">
        </form>
    </main>


</body>

</html>