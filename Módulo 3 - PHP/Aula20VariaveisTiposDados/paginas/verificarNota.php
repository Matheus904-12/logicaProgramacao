<?php
include '../conexao/conexao.php';

// Buscar notas por curso (select com 4 opções)
$message = '';
$rows = [];
$selectedCourse = '';

// Defina aqui as opções de curso (código => rótulo)
$cursos = [
    'ads' => 'Análise e Desenvolvimento de Sistemas',
    'es'  => 'Engenharia de Software',
    'si'  => 'Sistemas da Informação',
    'cc'  => 'Ciência da Computação'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedCourse = isset($_POST['curso']) ? trim($_POST['curso']) : '';

    if ($selectedCourse === '' || !array_key_exists($selectedCourse, $cursos)) {
        $message = 'Selecione um curso válido.';
    } else {
        $sql = "SELECT id, nome, email, nota_atividade, nota_prova, nota_final FROM usuarios WHERE curso = ? ORDER BY nome ASC";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param('s', $selectedCourse);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                while ($r = $res->fetch_assoc()) {
                    $rows[] = [
                        'id' => $r['id'],
                        'nome' => $r['nome'],
                        'email' => $r['email'],
                        'atividade' => is_null($r['nota_atividade']) ? null : floatval($r['nota_atividade']),
                        'prova' => is_null($r['nota_prova']) ? null : floatval($r['nota_prova']),
                        'final' => is_null($r['nota_final']) ? null : floatval($r['nota_final']),
                    ];
                }
                if (count($rows) === 0) {
                    $message = 'Nenhum aluno encontrado para o curso selecionado.';
                }
            } else {
                $message = 'Erro ao executar a consulta: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = 'Erro ao preparar a consulta: ' . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Nota</title>
    <link rel="stylesheet" href="../estilos/styleVerificar.css">
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
        <h1>Verificar Nota</h1>

        <form method="post" class="verificar-form">
            <label for="curso">Curso:</label>
            <select id="curso" name="curso" required>
                <option value="">-- selecione o curso --</option>
                <?php foreach ($cursos as $code => $label): ?>
                    <option value="<?php echo htmlspecialchars($code); ?>" <?php echo ($selectedCourse === $code) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-open-modal">Filtrar</button>
        </form>

        <div class="resultado">
            <?php if ($message): ?>
                <div class="mensagem erro"><?php echo htmlspecialchars($message); ?></div>
            <?php elseif (!empty($rows)): ?>
                <div class="table-wrap">
                    <table class="nota-table">
                        <thead>
                            <tr><th>ID</th><th>Nome</th><th>Email</th><th>Atividade</th><th>Prova</th><th>Final</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <td data-label="ID"><?php echo htmlspecialchars($r['id']); ?></td>
                                <td data-label="Nome"><?php echo htmlspecialchars($r['nome']); ?></td>
                                <td data-label="Email"><?php echo htmlspecialchars($r['email']); ?></td>
                                <td data-label="Atividade"><?php echo is_null($r['atividade']) ? '—' : number_format($r['atividade'], 2, ',', '.'); ?></td>
                                <td data-label="Prova"><?php echo is_null($r['prova']) ? '—' : number_format($r['prova'], 2, ',', '.'); ?></td>
                                <td data-label="Final"><?php echo is_null($r['final']) ? '—' : number_format($r['final'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>