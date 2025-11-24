<?php
require_once 'database.php';

$livros = [];
try {
    $stmt = $pdo->query("SELECT id, titulo, autor, ano_publicacao FROM livros ORDER BY titulo ASC");
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erro ao carregar a lista de livros: " . $e->getMessage();
}

$msg = filter_input(INPUT_GET, 'msg', FILTER_SANITIZE_SPECIAL_CHARS);
$error = isset($error) ? $error : filter_input(INPUT_GET, 'error', FILTER_SANITIZE_SPECIAL_CHARS);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Livraria Simples</title>
    
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
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #000; 
            padding: 8px;
        }
        th {
            background-color: #eee; 
        }
        .delete-btn {
            background-color: #f00; 
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .message {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 0;
            border: 1px solid;
            font-weight: normal;
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

    <div class="container">
        <h1>Sistema de Livraria (PHP/SQLite)</h1>

        <?php if ($msg): ?>
            <div class="message success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <hr>
        <h2>➕ Adicionar Novo Livro</h2>
        <form action="add_book.php" method="POST">
            <div class="form-group">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
            </div>
            <div class="form-group">
                <label for="autor">Autor:</label>
                <input type="text" id="autor" name="autor" required>
            </div>
            <div class="form-group">
                <label for="ano_publicacao">Ano de Publicação:</label>
                <input type="number" id="ano_publicacao" name="ano_publicacao" required min="1000" max="<?php echo date('Y'); ?>">
            </div>
            <button type="submit">Adicionar Livro</button>
        </form>

        <hr>

        <h2>📖 Livros Cadastrados</h2>

        <?php if (empty($livros)): ?>
            <p>Nenhum livro cadastrado. Adicione um acima!</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Ano</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livros as $livro): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($livro['id']); ?></td>
                            <td><?php echo htmlspecialchars($livro['titulo']); ?></td>
                            <td><?php echo htmlspecialchars($livro['autor']); ?></td>
                            <td><?php echo htmlspecialchars($livro['ano_publicacao']); ?></td>
                            <td>
                                <button 
                                    class="delete-btn" 
                                    onclick="confirmDelete(<?php echo htmlspecialchars($livro['id']); ?>, '<?php echo htmlspecialchars($livro['titulo']); ?>')"
                                >
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function confirmDelete(id, titulo) {
            if (confirm('Tem certeza que deseja excluir o livro: "' + titulo + '" (ID: ' + id + ')?')) {
                window.location.href = 'delete_book.php?id=' + id;
            }
        }
    </script>
</body>
</html>