<?php
require 'conexão.php';
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
            $endereco = $_POST['endereco'];
            $cidade = $_POST['cidade'];
            $estado = strtoupper($_POST['estado']);
            $cep = $_POST['cep'];
            $nome = $_POST['nome'];

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
                echo '<div class="error">Erro ao incluir o associado: ' . $e->getMessage() . '</div>';
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
                        echo '<option value="' . htmlspecialchars($row['pla_numero']) . '">' . htmlspecialchars($row['pla_descricao']) . ' - R$ ' . htmlspecialchars($row['pla_valor']) . '</option>';
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

        <!-- Restante do código, como Alterar, Excluir, Buscar e Listagem, com as devidas modificações -->
