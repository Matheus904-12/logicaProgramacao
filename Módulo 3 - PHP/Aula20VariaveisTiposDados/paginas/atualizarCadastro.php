<?php

include '../conexao/conexao.php';

// variáveis iniciais
$message = '';
$email_check_done = false;
$email = '';
$nome = '';
$sobrenome = '';
$curso = '';
$user_id = null;

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ação: buscar ou atualizar
        $acao = isset($_POST['acao']) ? $_POST['acao'] : '';
        $email_raw = isset($_POST['email']) ? $_POST['email'] : '';
        $email = strtolower(trim($email_raw)); // normalização

        // valida email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Email inválido. Informe um endereço de email válido.';
        } else {
            if ($acao === 'buscar') {
                // buscar registro completo por email
                $stmt = $conn->prepare("SELECT id, nome, sobrenome, curso, email FROM usuarios WHERE email = ? LIMIT 1");
                if ($stmt) {
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
                    $stmt->bind_result($id_res, $nome_res, $sob_res, $curso_res, $email_res);
                    if ($stmt->fetch()) {
                        $user_id = $id_res;
                        $nome = $nome_res;
                        $sobrenome = $sob_res;
                        $curso = $curso_res;
                        $email = $email_res;
                        $message = 'Usuário encontrado. Edite os campos abaixo e clique em Atualizar.';
                    } else {
                        $message = 'Nenhum cadastro encontrado para o email informado.';
                    }
                    $stmt->close();
                } else {
                    throw new Exception('Erro no prepare (buscar): ' . $conn->error);
                }
            } elseif ($acao === 'atualizar') {
                // Atualiza por ID (seguro mesmo se alterar email)
                $user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : null;
                $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
                $sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : '';
                $curso = isset($_POST['curso']) ? trim($_POST['curso']) : '';
                $new_email_raw = isset($_POST['email']) ? trim($_POST['email']) : '';
                $new_email = strtolower($new_email_raw);

                if ($user_id === null) {
                    $message = 'Usuário não especificado para atualização.';
                } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                    $message = 'Email inválido. Informe um endereço de email válido.';
                } elseif ($nome === '') {
                    $message = 'Nome vazio — nada a atualizar.';
                } else {
                    $updStmt = $conn->prepare("UPDATE usuarios SET nome = ?, sobrenome = ?, email = ?, curso = ? WHERE id = ?");
                    if (!$updStmt) {
                        throw new Exception('Erro na preparação da consulta: ' . $conn->error);
                    }
                    $updStmt->bind_param('ssssi', $nome, $sobrenome, $new_email, $curso, $user_id);
                    if ($updStmt->execute()) {
                        if ($updStmt->affected_rows > 0) {
                            $message = "Cadastro atualizado com sucesso.";
                        } else {
                            $message = "Nenhuma alteração realizada.";
                        }
                    } else {
                        throw new Exception('Erro na execução da consulta: ' . $updStmt->error);
                    }
                    $updStmt->close();
                }
            }
        }
    }
} catch (Exception $e) {
    $message = 'Exceção capturada: ' . $e->getMessage();
}

// Não fechamos o $conn aqui porque vamos usá-lo para listar usuários abaixo; fecharemos ao final do script.

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Cadastro</title>
    <link rel="stylesheet" href="../estilos/styleCadastrar.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="../index.html">Início</a></li>
                <li><a href="cadastro.php">Cadastrar Usuário</a></li>
                <li><a href="verificarCadastro.php">Verificar Usuário</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="page-grid">
        <section id="containerSection">
            <h2>Buscar / Atualizar Cadastro</h2>
            <?php if ($message): ?>
                <div class="mensagem aviso"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <div class="edit-container">
                <form method="post" class="buscar-form">
                    <input type="hidden" name="acao" value="buscar">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </form>

                <form method="post" class="update-form" style="margin-top: 1rem;">
                    <input type="hidden" name="acao" value="atualizar">
                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="sobrenome">Sobrenome</label>
                            <input type="text" id="sobrenome" name="sobrenome" value="<?php echo htmlspecialchars($sobrenome); ?>">
                        </div>

                        <div class="form-group">
                            <label for="curso">Curso</label>
                            <input type="text" id="curso" name="curso" value="<?php echo htmlspecialchars($curso); ?>">
                        </div>

                        <div class="form-group">
                            <label for="email_edit">Email</label>
                            <input type="email" id="email_edit" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">Atualizar</button>
                    </div>
                </form>
            </div>
    </section>
        
    <section>
            <h2>Lista de Usuários</h2>
            <table>
                <thead>
                    <tr><th>ID</th><th>Nome</th><th>Email</th><th>Ações</th></tr>
                </thead>
                <tbody>
                <?php
                // listar alguns usuários para edição rápida
                $listSql = "SELECT id, nome, email FROM usuarios ORDER BY id DESC LIMIT 100";
                if ($res = $conn->query($listSql)) {
                    if ($res->num_rows > 0) {
                        while ($r = $res->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($r['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($r['nome']) . '</td>';
                            echo '<td>' . htmlspecialchars($r['email']) . '</td>';
                            echo '<td><form method="POST" class="action-form"><input type="hidden" name="email" value="' . htmlspecialchars($r['email'], ENT_QUOTES) . '"><button type="submit" name="acao" value="buscar" class="action-btn edit">Editar</button></form></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4">Nenhum usuário cadastrado.</td></tr>';
                    }
                    $res->free();
                } else {
                    echo '<tr><td colspan="4">Erro ao listar usuários.</td></tr>';
                }
                ?>
                </tbody>
            </table>
    </section>
    </div>
    <?php
        // Fecha conexão
        if (isset($conn) && $conn) { $conn->close(); }
        ?>
    </main>

</body>
</html>