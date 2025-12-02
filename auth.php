<?php
/**
 * Обработчик авторизации
 */

require_once 'config.php';

// Проверяем, что запрос POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

// Получаем и валидируем входные данные
$login = isset($_POST['login']) ? sanitizeInput($_POST['login']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$errors = [];

// Валидация
if (empty($login)) {
    $errors[] = 'Введите email или имя пользователя';
}

if (empty($password)) {
    $errors[] = 'Введите пароль';
}

// Если есть ошибки, возвращаемся на страницу входа
if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    $_SESSION['login_data'] = ['login' => $login];
    redirect('login.php');
}

try {
    $pdo = getDBConnection();
    
    // Поиск пользователя по email или username
    $stmt = $pdo->prepare("SELECT id, username, email, password FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$login, $login]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Успешная авторизация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        
        // Очищаем временные данные
        unset($_SESSION['login_errors']);
        unset($_SESSION['login_data']);
        
        // Редирект на главную страницу
        redirect('index.php');
    } else {
        // Неверные учетные данные
        $_SESSION['login_errors'] = ['Неверный email/имя пользователя или пароль'];
        $_SESSION['login_data'] = ['login' => $login];
        redirect('login.php');
    }
    
} catch (PDOException $e) {
    $_SESSION['login_errors'] = ['Ошибка при авторизации. Попробуйте позже.'];
    redirect('login.php');
}
