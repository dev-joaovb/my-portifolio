<?php
// Configurações do Banco de Dados
$host = 'br120.hostgator.com.br';
$database = 'elet9522_snmbg';
$user = 'elet9522_composer';
$pass = 'H@rm0n1@1997';

try {
    // 1. Conexão com o banco
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        // Sanatização básica dos inputs
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
        $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($nome && $email && $mensagem) {
            // 3. Prepara a Query (Proteção total contra SQL Injection)
            $sql = "INSERT INTO contatos (nome, email, telefone, mensagem) VALUES (:nome, :email, :telefone, :mensagem)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':mensagem', $mensagem);

            if ($stmt->execute()) {
                // Sucesso! Redireciona de volta com um parâmetro de sucesso
                header("Location: index.html?status=sucesso#contato");
            } else {
                header("Location: index.html?status=erro#contato");
            }
        } else {
            header("Location: index.html?status=dados_invalidos#contato");
        }
    }
} catch (PDOException $e) {
    // Em produção, não exiba o erro real ($e). Logue-o em um arquivo.
    die("Erro ao conectar: " . $e->getMessage());
}