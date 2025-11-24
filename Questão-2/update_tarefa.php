<?php
require_once 'database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$status = filter_input(INPUT_GET, 'status', FILTER_VALIDATE_INT);
$novo_status = ($status === 0) ? 0 : 1; 

if ($id) {
    try {
        $stmt = $pdo->prepare("UPDATE tarefas SET concluida = ? WHERE id = ?");
        
        $stmt->execute([$novo_status, $id]);
        
        $msg = ($novo_status === 1) ? 'Tarefa marcada como concluída!' : 'Tarefa marcada como não concluída!';

        header('Location: index.php?msg=' . urlencode($msg));
        exit;
    } catch (PDOException $e) {
        header('Location: index.php?error=Erro ao atualizar a tarefa: ' . urlencode($e->getMessage()));
        exit;
    }
} else {
    header('Location: index.php?error=ID de tarefa inválido!');
    exit;
}
?>