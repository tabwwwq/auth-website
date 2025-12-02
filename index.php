<?php
// Начинаем сессию
session_start();

// Проверяем авторизацию пользователя
if (!isset($_SESSION['user_id'])) {
    // Если не авторизован, перенаправляем на страницу входа
    header('Location: login.php');
    exit;
}

// Подключаем конфигурацию базы данных
require_once 'config.php';

try {
    // Получаем список всех пользователей
    $stmt = $pdo->prepare("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Ошибка при получении списка пользователей: ' . $e->getMessage();
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная - Auth Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container container-wide">
        <div class="welcome">
            <h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <p>Вы успешно вошли в систему</p>
        </div>
        
        <h1>Список зарегистрированных пользователей</h1>
        
        <?php if (isset($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (count($users) > 0): ?>
            <table class="users-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Имя пользователя</th>
                        <th>Email</th>
                        <th>Дата регистрации</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($user['created_at']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="user-count">
                Всего пользователей: <?php echo count($users); ?>
            </div>
        <?php else: ?>
            <div class="message error">Пользователи не найдены</div>
        <?php endif; ?>
        
        <a href="logout.php" class="btn btn-logout">Выйти</a>
    </div>
</body>
</html>
