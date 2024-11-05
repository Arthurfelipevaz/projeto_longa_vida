<?php
require 'conexão.php'; // Assegure-se de que este arquivo está correto e funcional
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Planos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Cadastro de Planos</h1>
        <div class="navigation">
            <a href="index.php">← Voltar para a Página Principal</a>
        </div>
    </header>
    
    <div class="container">
        <!-- Incluir Plano -->
        <h2>Incluir Plano</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['incluir'])) {
            $numero = $_POST['numero'];
            $descricao = $_POST['descricao'];
            $valor = $_POST['valor'];

            try {
                $sql = "INSERT INTO plano (pla_numero, pla_descricao, pla_valor) 
                        VALUES (:numero, :descricao, :valor)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':descricao' => $descricao,
                    ':valor' => $valor
                ]);
                echo '<div class="alert">Plano incluído com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao incluir o plano: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="plano.php" method="post">
            <input type="hidden" name="incluir" value="1">
            
            <label for="numero">Número do Plano:</label>
            <input type="number" name="numero" id="numero" required>
            
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" id="descricao" maxlength="30" required>
            
            <label for="valor">Valor (R$):</label>
            <input type="number" step="0.01" name="valor" id="valor" required>
            
            <button type="submit">Incluir Plano</button>
        </form>

        <!-- Alterar Plano -->
        <h2>Alterar Plano</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar'])) {
            $numero_original = $_POST['numero_original'];
            $numero = $_POST['numero'];
            $descricao = $_POST['descricao'];
            $valor = $_POST['valor'];

            try {
                $sql = "UPDATE plano 
                        SET pla_numero = :numero, pla_descricao = :descricao, pla_valor = :valor 
                        WHERE pla_numero = :numero_original";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':numero' => $numero,
                    ':descricao' => $descricao,
                    ':valor' => $valor,
                    ':numero_original' => $numero_original
                ]);
                echo '<div class="alert">Plano alterado com sucesso!</div>';
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao alterar o plano: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }

        $numero_alterar = '';
        $descricao_alterar = '';
        $valor_alterar = '';
        $numero_original = '';

        if (isset($_GET['alterar_numero'])) {
            $numero_original = $_GET['alterar_numero'];
            try {
                $sql = "SELECT * FROM plano WHERE pla_numero = :numero";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':numero' => $numero_original]);
                $plano = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($plano) {
                    $numero_alterar = $plano['pla_numero'];
                    $descricao_alterar = $plano['pla_descricao'];
                    $valor_alterar = $plano['pla_valor'];
                } else {
                    echo '<div class="error">Plano não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o plano: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="plano.php" method="post">
            <input type="hidden" name="alterar" value="1">
            
            <?php if ($numero_original): ?>
                <input type="hidden" name="numero_original" value="<?php echo htmlspecialchars($numero_original); ?>">
            <?php endif; ?>

            <label for="numero">Número do Plano:</label>
            <input type="number" name="numero" id="numero" value="<?php echo htmlspecialchars($numero_alterar); ?>" required>
            
            <label for="descricao">Descrição:</label>
            <input type="text" name="descricao" id="descricao" maxlength="30" value="<?php echo htmlspecialchars($descricao_alterar); ?>" required>
            
            <label for="valor">Valor (R$):</label>
            <input type="number" step="0.01" name="valor" id="valor" value="<?php echo htmlspecialchars($valor_alterar); ?>" required>
            
            <button type="submit">Alterar Plano</button>
        </form>

        <!-- Excluir Plano -->
        <h2>Excluir Plano</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir'])) {
            $numero = $_POST['numero_excluir'];

            try {
                // Verifica se existem associados vinculados a este plano
                $sql_verifica = "SELECT COUNT(*) FROM associado WHERE cli_plano = :numero";
                $stmt_verifica = $pdo->prepare($sql_verifica);
                $stmt_verifica->execute([':numero' => $numero]);
                $count = $stmt_verifica->fetchColumn();

                if ($count > 0) {
                    echo '<div class="error">Não é possível excluir o plano. Existem associados vinculados a este plano.</div>';
                } else {
                    $sql = "DELETE FROM plano WHERE pla_numero = :numero";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':numero' => $numero]);

                    if ($stmt->rowCount()) {
                        echo '<div class="alert">Plano excluído com sucesso!</div>';
                    } else {
                        echo '<div class="error">Plano não encontrado.</div>';
                    }
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao excluir o plano: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="plano.php" method="post">
            <input type="hidden" name="excluir" value="1">
            
            <label for="numero_excluir">Número do Plano:</label>
            <input type="number" name="numero_excluir" id="numero_excluir" required>
            
            <button type="submit">Excluir Plano</button>
        </form>

        <!-- Buscar Plano -->
        <h2>Buscar Plano</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar'])) {
            $numero = $_GET['buscar'];

            try {
                $sql = "SELECT * FROM plano WHERE pla_numero = :numero";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([':numero' => $numero]);
                $plano = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($plano) {
                    echo '<h3>Detalhes do Plano</h3>';
                    echo '<p><strong>Número:</strong> ' . htmlspecialchars($plano['pla_numero']) . '</p>';
                    echo '<p><strong>Descrição:</strong> ' . htmlspecialchars($plano['pla_descricao']) . '</p>';
                    echo '<p><strong>Valor:</strong> R$ ' . htmlspecialchars(number_format($plano['pla_valor'], 2, ',', '.')) . '</p>';
                } else {
                    echo '<div class="error">Plano não encontrado.</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="error">Erro ao buscar o plano: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
        }
        ?>
        <form action="plano.php" method="get">
            <label for="buscar_numero">Número do Plano:</label>
            <input type="number" name="buscar" id="buscar_numero" required>
            
            <button type="submit">Buscar Plano</button>
        </form>

        <!-- Listagem de Planos -->
        <h2>Listagem de Planos</h2>
        <?php
        try {
            $sql = "SELECT * FROM plano";
            $stmt = $pdo->query($sql);
            $planos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($planos) {
                echo '<div class="table-container">';
                echo '<table>';
                echo '<tr><th>Número</th><th>Descrição</th><th>Valor (R$)</th><th>Ações</th></tr>';
                foreach ($planos as $plano) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($plano['pla_numero']) . '</td>';
                    echo '<td>' . htmlspecialchars($plano['pla_descricao']) . '</td>';
                    echo '<td>' . htmlspecialchars(number_format($plano['pla_valor'], 2, ',', '.')) . '</td>';
                    echo '<td>';
                    echo '<a href="plano.php?alterar_numero=' . urlencode($plano['pla_numero']) . '">Alterar</a> | ';
                    echo '<a href="plano.php?buscar=' . urlencode($plano['pla_numero']) . '">Buscar</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p>Nenhum plano encontrado.</p>';
            }
        } catch (PDOException $e) {
            echo '<div class="error">Erro ao listar os planos: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>
</body>
</html>
