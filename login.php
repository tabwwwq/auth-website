<?php
// Начинаем сессию
session_start();

// Если пользователь уже авторизован, перенаправляем на главную
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Подключаем конфигурацию базы данных
require_once 'config.php';

// Инициализация переменных
$error = '';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные формы
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Валидация данных
    if (empty($username) || empty($password)) {
        $error = 'Все поля обязательны для заполнения';
    } else {
        try {
            // Ищем пользователя в базе данных
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            // Проверяем существование пользователя и корректность пароля
            if ($user && password_verify($password, $user['password'])) {
                // Успешная авторизация - создаем сессию
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Перенаправляем на главную страницу
                header('Location: index.php');
                exit;
            } else {
                $error = 'Неверное имя пользователя или пароль';
            }
        } catch (PDOException $e) {
            $error = 'Ошибка входа: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - Auth Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Вход</h1>
        <h2>Войдите в свой аккаунт</h2>
        
        <?php if ($error): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Имя пользователя:</label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required>
            </div>
            
            <button type="submit">Войти</button>
        </form>
        
        <div class="links">
            Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
        </div>
    </div>
</body>
</html>
