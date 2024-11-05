<?php
require 'conexão.php'; // Certifique-se de que o nome do arquivo está correto e que a conexão está configurada para o banco de dados 'longavida'
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Associados</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Associados</h1>
        <div class="navigation">
            <a href="index.php">← Voltar para a Página Principal</a>
        </div>
    </header>
    
    <div class="container">
        
        <h2>Incluir Associado</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            
            $plano = $_POST['plano'];
            $endereco = trim($_POST['endereco']);
            $cidade = trim($_POST['cidade']);
            $estado = strtoupper(trim($_POST['estado']));
            $cep = trim($_POST['cep']);
            $nome = trim($_POST['nome']);

            try {
                
                $sql = "INSERT INTO associado (cli_nome, cli_plano, cli_Endereco, cli_cidade, cli_estado, cli_cep) 
                        VALUES (:nome, :plano, :endereco, :cidade, :estado, :cep)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':plano' => $plano,
                    ':endereco' => $endereco,
                    ':cidade' => $cidade,
                    ':estado' => $estado,
                    ':cep' => $cep
                ]);
                echo '<div class="alert">Associado incluído com sucesso!</div>';
            } catch (PDOException $e) {
            
                echo '<div class="error">Erro ao incluir o associado: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="cadastro_associado.php" method="post">
            <input type="hidden" name="incluir" value="1">
            
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" maxlength="40" required>

            <label for="plano">Plano:</label>
            <select name="plano" id="plano" required>
                <option value="">Selecione</option>
                <?php
                try {
                    $sql = "SELECT pla_numero, pla_descricao, pla_valor FROM plano";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<option value="' . htmlspecialchars($row['pla_numero']) . '">' . htmlspecialchars($row['pla_descricao']) . ' - R$ ' . htmlspecialchars(number_format($row['pla_valor'], 2, ',', '.')) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Erro ao carregar planos</option>';
                }
                ?>
            </select>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" maxlength="35" required>

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" maxlength="20" required>

            <label for="estado">Estado:</label>
            <input type="text" name="estado" id="estado" maxlength="2" required>

            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" maxlength="9" placeholder="Ex: 12345-678" required>

            <button type="submit">Incluir Associado</button>
        </form>
                
        <h2>Alterar Associado</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $nome_original = trim($_POST['nome_original']);
            $nome = trim($_POST['nome']);
            $plano = $_POST['plano'];
            $endereco = trim($_POST['endereco']);
            $cidade = trim($_POST['cidade']);
            $estado = strtoupper(trim($_POST['estado']));
            $cep = trim($_POST['cep']);

            try {
                $sql = "UPDATE associado 
                        SET cli_nome = :nome, cli_plano = :plano, cli_Endereco = :endereco, 
                            cli_cidade = :cidade, cli_estado = :estado, cli_cep = :cep 
                        WHERE cli_nome = :nome_original";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nome' => $nome,
                    ':plano' => $plano,
                    ':endereco' => $endereco,
                    ':cidade' => $cidade,
                    ':estado' => $estado,
                    ':cep' => $cep,
                    ':nome_original' => $nome_original
                ]);
                echo '<div class="alert">Associado alterado com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar o associado: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }

        $nome_alterar = '';
        $plano_alterar = '';
        $endereco_alterar = '';
        $cidade_alterar = '';
        $estado_alterar = '';
        $cep_alterar = '';
        $nome_original = '';

        if (isset($_GET['alterar_nome'])) {
            $nome_original = $_GET['alterar_nome'];
            try {
                $sql = "SELECT * FROM associado WHERE cli_nome = :nome";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':nome' => $nome_original]);
                $associado = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($associado) {
                    $nome_alterar = $associado['cli_nome'];
                    $plano_alterar = $associado['cli_plano'];
                    $endereco_alterar = $associado['cli_Endereco'];
                    $cidade_alterar = $associado['cli_cidade'];
                    $estado_alterar = $associado['cli_estado'];
                    $cep_alterar = $associado['cli_cep'];
                } else {
                    echo '<div class="error">Associado não encontrado.</div>';
                }
            } catch (PDOException $e) {
                // Trata erros na busca do associado
                echo '<div class="error">Erro ao buscar o associado: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="cadastro_associado.php" method="post">
            <input type="hidden" name="alterar" value="1">
            
            <?php if ($nome_original): ?>
                <input type="hidden" name="nome_original" value="<?php echo htmlspecialchars($nome_original); ?>">
            <?php endif; ?>

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" maxlength="40" value="<?php echo htmlspecialchars($nome_alterar); ?>" required>

            <label for="plano">Plano:</label>
            <select name="plano" id="plano" required>
                <option value="">Selecione</option>
                <?php
                try {
                    $sql = "SELECT pla_numero, pla_descricao, pla_valor FROM plano";
                    $stmt = $pdo->query($sql);
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($plano_alterar == $row['pla_numero']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($row['pla_numero']) . '" ' . $selected . '>' . htmlspecialchars($row['pla_descricao']) . ' - R$ ' . htmlspecialchars(number_format($row['pla_valor'], 2, ',', '.')) . '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">Erro ao carregar planos</option>';
                }
                ?>
            </select>

            <label for="endereco">Endereço:</label>
            <input type="text" name="endereco" id="endereco" maxlength="35" value="<?php echo htmlspecialchars($endereco_alterar); ?>" required>

            <label for="cidade">Cidade:</label>
            <input type="text" name="cidade" id="cidade" maxlength="20" value="<?php echo htmlspecialchars($cidade_alterar); ?>" required>

            <label for="estado">Estado:</label>
            <input type="text" name="estado" id="estado" maxlength="2" value="<?php echo htmlspecialchars($estado_alterar); ?>" required>

            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" maxlength="9" placeholder="Ex: 12345-678" value="<?php echo htmlspecialchars($cep_alterar); ?>" required>

            <button type="submit">Alterar Associado</button>
        </form>

        
        <!-- Excluir Associado -->
        <h2>Excluir Associado</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $nome = trim($_POST['nome_excluir']);

            try {
                $sql = "DELETE FROM associado WHERE cli_nome = :nome";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':nome' => $nome]);

                if ($stmt->rowCount()) {
                    echo '<div class="alert">Associado excluído com sucesso!</div>';
                } else {
                    echo '<div class="error">Associado não encontrado.</div>';
                }
            } catch (PDOException $e) {
                // Trata erros na exclusão
                echo '<div class="error">Erro ao excluir o associado: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="cadastro_associado.php" method="post">
            <input type="hidden" name="excluir" value="1">
            
            <label for="nome_excluir">Nome do Associado:</label>
            <input type="text" name="nome_excluir" id="nome_excluir" maxlength="40" required>

            <button type="submit">Excluir Associado</button>
        </form>

        
        <!-- Buscar Associado -->
        <h2>Buscar Associado</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $nome = trim($_GET['buscar']);

            try {
                $sql = "SELECT a.cli_nome, a.cli_Endereco, a.cli_cidade, a.cli_estado, a.cli_cep, p.pla_descricao, p.pla_valor 
                        FROM associado a
                        JOIN plano p ON a.cli_plano = p.pla_numero 
                        WHERE a.cli_nome = :nome";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':nome' => $nome]);
                $associado = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($associado) {
                    echo '<h3>Detalhes do Associado</h3>';
                    echo '<p><strong>Nome:</strong> ' . htmlspecialchars($associado['cli_nome']) . '</p>';
                    echo '<p><strong>Plano:</strong> ' . htmlspecialchars($associado['pla_descricao']) . ' - R$ ' . htmlspecialchars(number_format($associado['pla_valor'], 2, ',', '.')) . '</p>';
                    echo '<p><strong>Endereço:</strong> ' . htmlspecialchars($associado['cli_Endereco']) . '</p>';
                    echo '<p><strong>Cidade:</strong> ' . htmlspecialchars($associado['cli_cidade']) . '</p>';
                    echo '<p><strong>Estado:</strong> ' . htmlspecialchars($associado['cli_estado']) . '</p>';
                    echo '<p><strong>CEP:</strong> ' . htmlspecialchars($associado['cli_cep']) . '</p>';
                } else {
                    echo '<div class="error">Associado não encontrado.</div>';
                }
            } catch (PDOException $e) {
                // Trata erros na busca
                echo '<div class="error">Erro ao buscar o associado: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="cadastro_associado.php" method="get">
            <label for="buscar_nome">Nome do Associado:</label>
            <input type="text" name="buscar" id="buscar_nome" maxlength="40" required>

            <button type="submit">Buscar Associado</button>
        </form>

        
        <!-- Listagem de Associados -->
        <h2>Listagem de Associados</h2>
        <?php
        try {
            $sql = "SELECT a.cli_nome, a.cli_Endereco, a.cli_cidade, a.cli_estado, a.cli_cep, p.pla_descricao, p.pla_valor 
                    FROM associado a
                    JOIN plano p ON a.cli_plano = p.pla_numero";
            $stmt = $pdo->query($sql);
            $associados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($associados) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr>
                        <th>Nome</th>
                        <th>Plano</th>
                        <th>Valor do Plano (R$)</th>
                        <th>Endereço</th>
                        <th>Cidade</th>
                        <th>Estado</th>
                        <th>CEP</th>
                        <th>Ações</th>
                      </tr>';
                foreach ($associados as $associado) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($associado['cli_nome']) . '</td>';
                    echo '<td>' . htmlspecialchars($associado['pla_descricao']) . '</td>';
                    echo '<td>' . htmlspecialchars(number_format($associado['pla_valor'], 2, ',', '.')) . '</td>';
                    echo '<td>' . htmlspecialchars($associado['cli_Endereco']) . '</td>';
                    echo '<td>' . htmlspecialchars($associado['cli_cidade']) . '</td>';
                    echo '<td>' . htmlspecialchars($associado['cli_estado']) . '</td>';
                    echo '<td>' . htmlspecialchars($associado['cli_cep']) . '</td>';
                    echo '<td>';
                    echo '<a href="cadastro_associado.php?alterar_nome=' . urlencode($associado['cli_nome']) . '">Alterar</a> | ';
                    echo '<a href="cadastro_associado.php?buscar=' . urlencode($associado['cli_nome']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhum associado encontrado.</p>';
            }
        } catch (PDOException $e) {
            // Trata erros na listagem
            echo '<div class="error">Erro ao listar os associados: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>
    <footer>
        <p>&copy; <?php echo date("Y"); ?> Longa Vida. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
