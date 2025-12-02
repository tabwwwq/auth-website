<?php
/**
 * Обработчик выхода из системы
 */

require_once 'config.php';

// Удаляем все данные сессии
$_SESSION = [];

// Удаляем cookie сессии
// Устанавливаем время истечения в прошлое, чтобы браузер удалил cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 1,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Уничтожаем сессию
session_destroy();

// Перенаправляем на страницу входа
header("Location: login.php");
exit();
