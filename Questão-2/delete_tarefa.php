<?php
require_once 'database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = ?");
        
        $stmt->execute([$id]);
        
        header('Location: index.php?msg=Tarefa excluída com sucesso!');
        exit;
    } catch (PDOException $e) {
        header('Location: index.php?error=Erro ao excluir a tarefa: ' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: index.php?error=ID de tarefa inválido!');
    exit;
}
?>