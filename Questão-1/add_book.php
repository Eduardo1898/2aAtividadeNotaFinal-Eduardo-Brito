<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
    $autor = filter_input(INPUT_POST, 'autor', FILTER_SANITIZE_SPECIAL_CHARS);

    $ano = filter_input(INPUT_POST, 'ano_publicacao', FILTER_VALIDATE_INT);

    if ($titulo && $autor && $ano) {
        try {
            $stmt = $pdo->prepare("INSERT INTO livros (titulo, autor, ano_publicacao) VALUES (?, ?, ?)");
            
            $stmt->execute([$titulo, $autor, $ano]);
            
            header('Location: index.php?msg=Livro adicionado com sucesso!');
            exit;
        } catch (PDOException $e) {
            header('Location: index.php?error=Erro ao adicionar o livro: ' . urlencode($e->getMessage()));
            exit;
        }
    } else {
        header('Location: index.php?error=Preencha todos os campos corretamente!');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>