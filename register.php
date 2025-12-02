<?php
/**
 * Страница регистрации
 */

require_once 'config.php';

// Если пользователь уже авторизован, перенаправляем на главную
if (isLoggedIn()) {
    redirect('index.php');
}

// Получаем ошибки и данные из сессии
$errors = isset($_SESSION['register_errors']) ? $_SESSION['register_errors'] : [];
$registerData = isset($_SESSION['register_data']) ? $_SESSION['register_data'] : [];

// Очищаем сессионные данные после получения
unset($_SESSION['register_errors']);
unset($_SESSION['register_data']);
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
        <div class="form-container">
            <h1>Регистрация</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form action="register_handler.php" method="POST" id="registerForm">
                <div class="form-group">
                    <label for="username">Имя пользователя</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        value="<?php echo htmlspecialchars($registerData['username'] ?? ''); ?>"
                        required
                        minlength="3"
                        maxlength="50"
                        pattern="[a-zA-Z0-9_]+"
                        placeholder="Введите имя пользователя"
                    >
                    <small>От 3 до 50 символов. Только буквы, цифры и подчеркивание.</small>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?php echo htmlspecialchars($registerData['email'] ?? ''); ?>"
                        required
                        maxlength="100"
                        placeholder="Введите email"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        minlength="6"
                        placeholder="Введите пароль"
                    >
                    <small>Минимум 6 символов</small>
                </div>
                
                <div class="form-group">
                    <label for="password_confirm">Подтвердите пароль</label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        required
                        minlength="6"
                        placeholder="Повторите пароль"
                    >
                </div>
                
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
            
            <p class="form-footer">
                Уже есть аккаунт? <a href="login.php">Войти</a>
            </p>
        </div>
    </div>
    
    <script>
        // Клиентская валидация
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirm').value;
            
            // Валидация имени пользователя
            if (!username) {
                e.preventDefault();
                alert('Введите имя пользователя');
                return;
            }
            
            if (username.length < 3 || username.length > 50) {
                e.preventDefault();
                alert('Имя пользователя должно быть от 3 до 50 символов');
                return;
            }
            
            if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                e.preventDefault();
                alert('Имя пользователя может содержать только буквы, цифры и подчеркивание');
                return;
            }
            
            // Валидация email
            if (!email) {
                e.preventDefault();
                alert('Введите email');
                return;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Введите корректный email');
                return;
            }
            
            // Валидация пароля
            if (!password) {
                e.preventDefault();
                alert('Введите пароль');
                return;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Пароль должен быть не менее 6 символов');
                return;
            }
            
            // Проверка совпадения паролей
            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Пароли не совпадают');
                return;
            }
        });
    </script>
</body>
</html>
