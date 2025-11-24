<?php
$dbFile = 'tarefas.sqlite';

try {
    $pdo = new PDO("sqlite:$dbFile");
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tarefas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            descricao TEXT NOT NULL,
            data_vencimento TEXT,
            concluida BOOLEAN NOT NULL DEFAULT 0 
            -- '0' é falso (não concluída), '1' é verdadeiro (concluída)
        )
    ");
    
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

?>