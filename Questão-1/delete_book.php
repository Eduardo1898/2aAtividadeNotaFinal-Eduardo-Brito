<?php
require_once 'database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM livros WHERE id = ?");
        
        $stmt->execute([$id]);
        
        header('Location: index.php?msg=Livro excluído com sucesso!');
        exit;
    } catch (PDOException $e) {
        header('Location: index.php?error=Erro ao excluir o livro: ' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: index.php?error=ID de livro inválido!');
    exit;
}
?>