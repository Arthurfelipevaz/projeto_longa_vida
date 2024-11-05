<?php
// conexao.php

$host = 'localhost';        // Endereço do servidor MySQL
$db   = 'longavida';       // Nome do banco de dados
$user = 'root';             // Seu usuário do MySQL
$pass = '';                 // Sua senha do MySQL (se houver)
$charset = 'utf8mb4';       // Charset

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";// verificação

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Habilita exceções para erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Define o modo de busca padrão
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desabilita emulação de prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
    exit;
}
?>
