<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Página Principal - Longa Vida</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .container {
            padding: 20px;
            text-align: center;
        }

        .menu {
            margin: 20px 0;
        }

        .menu a {
            display: inline-block;
            margin: 10px;
            padding: 20px 40px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: #218838;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Cadastro - Longa Vida</h1>
    </header>
    
    <div class="container">
        <h2>Bem-vindo ao Sistema de Cadastro</h2>
        <p>Escolha uma das opções abaixo para gerenciar seus dados.</p>
        
        <div class="menu">
            <a href="cadastro_associado.php">Gerenciar associados
            </a>
            <a href="plano.php">Gerenciar Planos</a>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Longa Vida. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
