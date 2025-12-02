<?php
// Начинаем сессию
session_start();

// Подключаем конфигурацию базы данных
require_once 'config.php';

// Инициализация переменных
$error = '';
$success = '';

// Обработка формы регистрации
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные формы
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Валидация данных
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Все поля обязательны для заполнения';
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = 'Имя пользователя должно быть от 3 до 50 символов';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Некорректный адрес электронной почты';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль должен содержать минимум 6 символов';
    } elseif ($password !== $confirm_password) {
        $error = 'Пароли не совпадают';
    } else {
        try {
            // Проверяем, существует ли пользователь с таким именем
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                $error = 'Пользователь с таким именем уже существует';
            } else {
                // Проверяем, существует ли пользователь с таким email
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                
                if ($stmt->fetch()) {
                    $error = 'Пользователь с таким email уже существует';
                } else {
                    // Хешируем пароль
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    // Вставляем нового пользователя в базу данных
                    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->execute([$username, $email, $hashed_password]);
                    
                    $success = 'Регистрация успешна! Теперь вы можете войти';
                    
                    // Перенаправляем на страницу входа через 2 секунды
                    header("refresh:2;url=login.php");
                }
            }
        } catch (PDOException $e) {
            $error = 'Ошибка регистрации: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - Auth Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Регистрация</h1>
        <h2>Создайте новый аккаунт</h2>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       required
                       minlength="3"
                       maxlength="50">
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       minlength="6">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Подтвердите пароль:</label>
                <input type="password" 
                       id="confirm_password" 
                       name="confirm_password" 
                       required
                       minlength="6">
            </div>
            
            <button type="submit">Зарегистрироваться</button>
        </form>
        
        <div class="links">
            Уже есть аккаунт? <a href="login.php">Войти</a>
        </div>
    </div>
</body>
</html>
