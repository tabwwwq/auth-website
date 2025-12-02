<?php
/**
 * Страница авторизации
 */

require_once 'config.php';

// Если пользователь уже авторизован, перенаправляем на главную
if (isLoggedIn()) {
    redirect('index.php');
}

// Получаем ошибки и данные из сессии
$errors = isset($_SESSION['login_errors']) ? $_SESSION['login_errors'] : [];
$loginData = isset($_SESSION['login_data']) ? $_SESSION['login_data'] : [];
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

// Очищаем сессионные данные после получения
unset($_SESSION['login_errors']);
unset($_SESSION['login_data']);
unset($_SESSION['success_message']);
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
        <div class="form-container">
            <h1>Вход в систему</h1>
            
            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="auth.php" method="POST" id="loginForm">
                <div class="form-group">
                    <label for="login">Email или имя пользователя</label>
                    <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        value="<?php echo htmlspecialchars($loginData['login'] ?? ''); ?>"
                        required
                        placeholder="Введите email или имя пользователя"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        placeholder="Введите пароль"
                    >
                </div>
                
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
            
            <p class="form-footer">
                Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
            </p>
        </div>
    </div>
    
    <script>
        // Клиентская валидация
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const login = document.getElementById('login').value.trim();
            const password = document.getElementById('password').value;
            
            if (!login) {
                e.preventDefault();
                alert('Введите email или имя пользователя');
                return;
            }
            
            if (!password) {
                e.preventDefault();
                alert('Введите пароль');
                return;
            }
        });
    </script>
</body>
</html>
