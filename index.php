<?php
/**
 * Главная страница - список всех пользователей
 * Доступна только авторизованным пользователям
 */

require_once 'config.php';

// Проверка авторизации
if (!isLoggedIn()) {
    redirect('login.php');
}

// Получение списка всех пользователей
try {
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT id, username, email, created_at FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $users = [];
    $error = "Ошибка при получении списка пользователей";
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
    <div class="container">
        <header class="header">
            <h1>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="logout.php" class="btn btn-logout">Выйти</a>
        </header>
        
        <main class="main-content">
            <h2>Список зарегистрированных пользователей</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($users)): ?>
                <div class="table-responsive">
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
                                <tr class="<?php echo $user['id'] == $_SESSION['user_id'] ? 'current-user' : ''; ?>">
                                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($user['username']); ?>
                                        <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                            <span class="badge">Вы</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo date('d.m.Y H:i', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="user-count">Всего пользователей: <?php echo count($users); ?></p>
            <?php else: ?>
                <p class="no-users">Пользователи не найдены</p>
            <?php endif; ?>
        </main>
        
        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> Auth Website. Все права защищены.</p>
        </footer>
    </div>
</body>
</html>
