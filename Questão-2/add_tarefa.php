<?php
require_once 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
    $data_vencimento = filter_input(INPUT_POST, 'data_vencimento', FILTER_SANITIZE_SPECIAL_CHARS);
    
    if ($descricao) {
        $data_vencimento = empty($data_vencimento) ? NULL : $data_vencimento;

        try {
            $stmt = $pdo->prepare("INSERT INTO tarefas (descricao, data_vencimento) VALUES (?, ?)");
            
            $stmt->execute([$descricao, $data_vencimento]);
            
            header('Location: index.php?msg=Tarefa adicionada com sucesso!');
            exit;
        } catch (PDOException $e) {
            header('Location: index.php?error=Erro ao adicionar a tarefa: ' . urlencode($e->getMessage()));
            exit;
        }
    } else {
        header('Location: index.php?error=A descrição da tarefa é obrigatória!');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>