<?php
/**
 * Обработчик регистрации
 */

require_once 'config.php';

// Проверяем, что запрос POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('register.php');
}

// Получаем и валидируем входные данные
$username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
$email = isset($_POST['email']) ? sanitizeInput($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';

$errors = [];

// Валидация имени пользователя
if (empty($username)) {
    $errors[] = 'Введите имя пользователя';
} elseif (strlen($username) < 3 || strlen($username) > 50) {
    $errors[] = 'Имя пользователя должно быть от 3 до 50 символов';
} elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = 'Имя пользователя может содержать только буквы, цифры и подчеркивание';
}

// Валидация email
if (empty($email)) {
    $errors[] = 'Введите email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Введите корректный email';
} elseif (strlen($email) > 100) {
    $errors[] = 'Email не должен превышать 100 символов';
}

// Валидация пароля
if (empty($password)) {
    $errors[] = 'Введите пароль';
} elseif (strlen($password) < 6) {
    $errors[] = 'Пароль должен быть не менее 6 символов';
}

// Проверка подтверждения пароля
if ($password !== $password_confirm) {
    $errors[] = 'Пароли не совпадают';
}

// Если есть ошибки валидации, возвращаемся на страницу регистрации
if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_data'] = [
        'username' => $username,
        'email' => $email
    ];
    redirect('register.php');
}

try {
    $pdo = getDBConnection();
    
    // Проверяем, существует ли пользователь с таким username или email (один запрос)
    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $existingUser = $stmt->fetch();
    
    if ($existingUser) {
        if ($existingUser['username'] === $username) {
            $errors[] = 'Пользователь с таким именем уже существует';
        }
        if ($existingUser['email'] === $email) {
            $errors[] = 'Пользователь с таким email уже существует';
        }
    }
    
    // Если есть ошибки, возвращаемся на страницу регистрации
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_data'] = [
            'username' => $username,
            'email' => $email
        ];
        redirect('register.php');
    }
    
    // Хэшируем пароль
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Вставляем нового пользователя
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashedPassword]);
    
    // Очищаем временные данные
    unset($_SESSION['register_errors']);
    unset($_SESSION['register_data']);
    
    // Устанавливаем сообщение об успехе
    $_SESSION['success_message'] = 'Регистрация успешна! Теперь вы можете войти.';
    
    // Редирект на страницу входа
    redirect('login.php');
    
} catch (PDOException $e) {
    $_SESSION['register_errors'] = ['Ошибка при регистрации. Попробуйте позже.'];
    $_SESSION['register_data'] = [
        'username' => $username,
        'email' => $email
    ];
    redirect('register.php');
}
