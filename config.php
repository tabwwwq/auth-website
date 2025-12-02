<?php
// Конфигурация подключения к базе данных
$host = 'localhost';
$dbname = 'auth_website';
$username = 'root';
$password = '';

// Создание подключения к базе данных
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Устанавливаем режим обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Устанавливаем режим выборки по умолчанию
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>
