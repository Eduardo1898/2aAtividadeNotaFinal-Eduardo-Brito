<?php
require_once 'database.php';

$nao_concluidas = [];
$concluidas = [];

try {
    $stmt = $pdo->query("SELECT * FROM tarefas ORDER BY concluida ASC, data_vencimento ASC, id DESC");
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tarefas as $tarefa) {
        if ($tarefa['concluida'] == 1) {
            $concluidas[] = $tarefa;
        } else {
            $nao_concluidas[] = $tarefa;
        }
    }

} catch (PDOException $e) {
    $error = "Erro ao carregar a lista de tarefas: " . $e->getMessage();
}

$msg = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
$error = isset($error) ? $error : filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS);

function formatarData($data) {
    return $data ? date('d/m/Y', strtotime($data)) : 'Sem data';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    
    <style>
        body {
            font-family: sans-serif; 
            margin: 20px;
        }
        h1, h2 {
            color: #000;
            border-bottom: 1px solid #ccc; 
            padding-bottom: 5px;
        }
        .task-list {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }
        .task-list li {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between; 
            align-items: center;
        }
        .task-concluida {
            background-color: #e6ffe6; 
            text-decoration: line-through; 
            color: #555;
            border-left: 5px solid #0a0;
        }
        .task-incompleta {
             border-left: 5px solid #f90;
        }
        .task-actions a {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            margin-left: 5px;
            font-size: 14px;
        }
        .btn-concluir {
            background-color: #28a745;
            color: white;
        }
        .btn-desmarcar {
            background-color: #ffc107;
            color: #333;
        }
        .btn-excluir {
            background-color: #dc3545;
            color: white;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 0;
            border: 1px solid;
        }
        .success {
            background-color: #dfd;
            color: #383;
            border-color: #383;
        }
        .error {
            background-color: #fdd;
            color: #833;
            border-color: #833;
        }
    </style>
</head>
<body>

    <h1>✅ Gerenciador de Tarefas (To-do List)</h1>

    <?php if ($msg): ?>
        <div class="message success"><?php echo htmlspecialchars($msg); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="message error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <hr>
    <h2>➕ Adicionar Nova Tarefa</h2>
    <form action="add_tarefa.php" method="POST">
        <label for="descricao">Descrição da Tarefa:</label><br>
        <input type="text" id="descricao" name="descricao" size="50" required><br><br>
        
        <label for="data_vencimento">Data de Vencimento (opcional):</label><br>
        <input type="date" id="data_vencimento" name="data_vencimento"><br><br>
        
        <button type="submit">Salvar Tarefa</button>
    </form>

    <hr>
    
    <h2>⏳ Tarefas Pendentes (<?php echo count($nao_concluidas); ?>)</h2>
    <?php if (empty($nao_concluidas)): ?>
        <p>Parabéns! Nenhuma tarefa pendente!</p>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($nao_concluidas as $tarefa): ?>
                <li class="task-incompleta">
                    <span>
                        **<?php echo htmlspecialchars($tarefa['descricao']); ?>** (Vencimento: <?php echo formatarData($tarefa['data_vencimento']); ?>)
                    </span>
                    <span class="task-actions">
                        <a href="update_tarefa.php?id=<?php echo $tarefa['id']; ?>" class="btn-concluir">
                            Concluir
                        </a>
                        <a href="#" class="btn-excluir" 
                           onclick="confirmDelete(<?php echo $tarefa['id']; ?>, '<?php echo htmlspecialchars($tarefa['descricao']); ?>'); return false;">
                            Excluir
                        </a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <hr>
    
    <h2>✅ Tarefas Concluídas (<?php echo count($concluidas); ?>)</h2>
    <?php if (empty($concluidas)): ?>
        <p>Nenhuma tarefa foi marcada como concluída ainda.</p>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($concluidas as $tarefa): ?>
                <li class="task-concluida">
                    <span>
                        <?php echo htmlspecialchars($tarefa['descricao']); ?> 
                        (Vencimento: <?php echo formatarData($tarefa['data_vencimento']); ?>)
                    </span>
                    <span class="task-actions">
                         <a href="update_tarefa.php?id=<?php echo $tarefa['id']; ?>&status=0" class="btn-desmarcar">
                            Desmarcar
                        </a>
                         <a href="#" class="btn-excluir" 
                           onclick="confirmDelete(<?php echo $tarefa['id']; ?>, '<?php echo htmlspecialchars($tarefa['descricao']); ?>'); return false;">
                            Excluir
                        </a>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <script>
        function confirmDelete(id, descricao) {
            if (confirm('Tem certeza que deseja excluir a tarefa: "' + descricao + '" (ID: ' + id + ')?')) {
                window.location.href = 'delete_tarefa.php?id=' + id;
            }
        }
    </script>
</body>
</html>