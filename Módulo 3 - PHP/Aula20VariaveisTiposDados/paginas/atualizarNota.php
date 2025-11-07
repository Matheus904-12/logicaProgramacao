<?php

include '../conexao/conexao.php';

// Mensagens de feedback
$mensagem = '';
$erro = '';

// Processa submissão de notas (botão Salvar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $saveId = (int) $_POST['salvar'];

    // Recupera valores enviados dos inputs dinâmicos
    $fieldAt = 'nota_atividade_' . $saveId;
    $fieldPv = 'nota_prova_' . $saveId;

    $notaAt = isset($_POST[$fieldAt]) ? str_replace(',', '.', $_POST[$fieldAt]) : '';
    $notaPv = isset($_POST[$fieldPv]) ? str_replace(',', '.', $_POST[$fieldPv]) : '';

    // Validação simples: números entre 0 e 10
    if ($notaAt === '' || !is_numeric($notaAt) || $notaPv === '' || !is_numeric($notaPv)) {
        $erro = 'Informe valores numéricos válidos para as notas (0 a 10).';
    } else {
        $notaAt = floatval($notaAt);
        $notaPv = floatval($notaPv);
        if ($notaAt < 0 || $notaAt > 10 || $notaPv < 0 || $notaPv > 10) {
            $erro = 'As notas devem estar entre 0 e 10.';
        } else {
            // Calcula nota final (média simples)
            $notaFinal = round((($notaAt + $notaPv) / 2), 2);

            // Atualiza a tabela usuarios
            $upd = $conn->prepare("UPDATE usuarios SET nota_atividade = ?, nota_prova = ?, nota_final = ? WHERE id = ?");
            if ($upd) {
                //ddd - duble
                $upd->bind_param('dddi', $notaAt, $notaPv, $notaFinal, $saveId);
                if ($upd->execute()) {
                    $mensagem = "Notas atualizadas para o usuário ID $saveId (final: $notaFinal).";
                } else {
                    $erro = 'Falha ao atualizar notas: ' . $upd->error;
                }
                $upd->close();
            } else {
                $erro = 'Erro no prepare de atualização: ' . $conn->error;
            }
        }
    }

    // Se o formulário da listagem enviar o curso, mantemos o filtro
    if (isset($_POST['curso'])) {
        $curso = $_POST['curso'];
    }
}

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

        <?php
        // Exibe mensagens de feedback após ações de salvar
        if (!empty($mensagem)) {
            echo "<div class='mensagem sucesso' style='margin:12px 0;'>" . htmlspecialchars($mensagem) . "</div>";
        }
        if (!empty($erro)) {
            echo "<div class='mensagem erro' style='margin:12px 0;'>" . htmlspecialchars($erro) . "</div>";
        }
        ?>

        <section>
            <form action="atualizarNota.php" method="POST" id="form-nota">
                <?php if (!empty(
                    $curso)) { echo '<input type="hidden" name="curso" value="' . htmlspecialchars($curso, ENT_QUOTES) . '">'; } ?>
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

                                    // botão que abre o modal de edição (passa dados via data-attributes)
                                    $safeAt = htmlspecialchars($notaAtividade, ENT_QUOTES);
                                    $safePv = htmlspecialchars($notaProva, ENT_QUOTES);
                                    echo "<div class='actions' style='white-space:nowrap;'>";
                                    echo "<button type='button' class='btn-open-modal' data-id='" . $row['id'] . "' data-notaat='" . $safeAt . "' data-notapv='" . $safePv . "'>Atualizar Nota</button>";
                                    echo "</div>";
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
        
        <!-- Modal de edição de notas -->
        <div id="notaModal" class="modal" aria-hidden="true" style="display:none;">
            <div class="modal-content">
                <button type="button" class="modal-close" aria-label="Fechar">&times;</button>
                <h3>Atualizar notas do aluno</h3>
                <form id="modalForm" action="atualizarNota.php" method="POST">
                    <input type="hidden" name="salvar" id="modal-salvar" value="">
                    <input type="hidden" name="curso" id="modal-curso" value="<?php echo isset($curso) ? htmlspecialchars($curso, ENT_QUOTES) : ''; ?>">
                    <div class="modal-row">
                        <label for="modal-atividade">Nota Atividade</label>
                        <input id="modal-atividade" class="nota-input" type="number" min="0" max="10" step="0.01" required>
                    </div>
                    <div class="modal-row">
                        <label for="modal-prova">Nota Prova</label>
                        <input id="modal-prova" class="nota-input" type="number" min="0" max="10" step="0.01" required>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="btn-salvar">Salvar</button>
                        <button type="button" class="btn-cancel modal-close">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <script>
    // JS para abrir o modal e preencher inputs
    (function(){
        function qs(sel, ctx){ return (ctx||document).querySelector(sel); }
        function qsa(sel, ctx){ return Array.from((ctx||document).querySelectorAll(sel)); }

        var modal = qs('#notaModal');
        var modalForm = qs('#modalForm');
        var modalSalvar = qs('#modal-salvar');
        var modalAt = qs('#modal-atividade');
        var modalPv = qs('#modal-prova');
        var modalCurso = qs('#modal-curso');

        qsa('.btn-open-modal').forEach(function(btn){
            btn.addEventListener('click', function(){
                var id = btn.getAttribute('data-id');
                var at = btn.getAttribute('data-notaat') || '';
                var pv = btn.getAttribute('data-notapv') || '';

                // Ajusta campos (names dinâmicos esperados pelo backend)
                modalSalvar.value = id;

                // Os nomes dinâmicos serão criados antes do submit: adicionamos hidden inputs com os nomes esperados
                // Remove antigos, se existirem
                var oldAt = qs('#modalForm input[name^="nota_atividade_"]'); if (oldAt) oldAt.remove();
                var oldPv = qs('#modalForm input[name^="nota_prova_"]'); if (oldPv) oldPv.remove();

                // Cria inputs com nomes dinamicos
                var inAt = document.createElement('input'); inAt.type='hidden'; inAt.name='nota_atividade_' + id; inAt.value=at; inAt.id='hid-at';
                var inPv = document.createElement('input'); inPv.type='hidden'; inPv.name='nota_prova_' + id; inPv.value=pv; inPv.id='hid-pv';
                modalForm.appendChild(inAt);
                modalForm.appendChild(inPv);

                // Preenche inputs visíveis (eles não tem names)
                modalAt.value = at;
                modalPv.value = pv;

                // Se houver input hidden de curso na página, mantemos seu valor
                if (qs('input[name="curso"]')) {
                    modalCurso.value = qs('input[name="curso"]').value;
                }

                modal.style.display = 'block';
                modal.setAttribute('aria-hidden','false');
            });
        });

        // Ao submeter, atualiza hidden dinamicos com valores atuais
        modalForm.addEventListener('submit', function(){
            var id = modalSalvar.value;
            var hidAt = qs('#hid-at');
            var hidPv = qs('#hid-pv');
            if (hidAt) hidAt.value = modalAt.value;
            if (hidPv) hidPv.value = modalPv.value;
        });

        // fechar
        qsa('.modal-close').forEach(function(b){ b.addEventListener('click', function(){ modal.style.display='none'; modal.setAttribute('aria-hidden','true'); }); });

        // fechar clicando fora do modal-content
        modal.addEventListener('click', function(e){ if (e.target === modal) { modal.style.display='none'; modal.setAttribute('aria-hidden','true'); } });
    })();
    </script>
</body>

</html>