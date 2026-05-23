<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', 'Group2');
    foreach ($pdo->query('SHOW DATABASES') as $row) {
        echo $row[0] . PHP_EOL;
    }
} catch (Throwable $e) {
    echo 'failed: ' . $e->getMessage() . PHP_EOL;
}
