# Auth Website

Простой веб-сайт с системой авторизации через базу данных MySQL. Проект позволяет регистрировать пользователей, входить в систему и просматривать список всех зарегистрированных пользователей.

## 📋 Возможности

- **Регистрация пользователей** - создание нового аккаунта с валидацией данных
- **Авторизация** - вход по email или имени пользователя
- **Список пользователей** - просмотр всех зарегистрированных пользователей (только для авторизованных)
- **Выход из системы** - безопасный logout
- **Безопасность** - хэширование паролей, защита от SQL-инъекций, валидация данных

## 🛠 Требования к системе

- **PHP** >= 7.4
- **MySQL** >= 5.7 или **MariaDB** >= 10.2
- **Веб-сервер** (Apache, Nginx, или встроенный PHP сервер)
- Расширение PHP **PDO** с драйвером MySQL

## 📁 Структура проекта

```
auth-website/
├── config.php            # Конфигурация подключения к БД
├── database.sql          # SQL скрипт для создания БД и таблицы
├── index.php             # Главная страница (список пользователей)
├── login.php             # Страница авторизации
├── register.php          # Страница регистрации
├── auth.php              # Обработчик авторизации
├── register_handler.php  # Обработчик регистрации
├── logout.php            # Обработчик выхода
├── style.css             # Стили для всех страниц
└── README.md             # Документация
```

## 🚀 Установка и настройка

### 1. Клонирование репозитория

```bash
git clone https://github.com/your-username/auth-website.git
cd auth-website
```

### 2. Настройка базы данных

#### Создание базы данных через MySQL CLI:

```bash
mysql -u root -p < database.sql
```

#### Или через phpMyAdmin:
1. Откройте phpMyAdmin
2. Импортируйте файл `database.sql`

#### Или вручную:

```sql
CREATE DATABASE IF NOT EXISTS auth_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE auth_website;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. Настройка конфигурации

#### Вариант 1: Через переменные окружения (рекомендуется для production)

```bash
export DB_HOST=localhost
export DB_NAME=auth_website
export DB_USER=your_db_user
export DB_PASS=your_db_password
```

#### Вариант 2: Редактирование config.php (для разработки)

Откройте файл `config.php` и измените параметры подключения к базе данных:

```php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');     // Хост базы данных
define('DB_NAME', getenv('DB_NAME') ?: 'auth_website');  // Имя базы данных
define('DB_USER', getenv('DB_USER') ?: 'root');          // Пользователь MySQL
define('DB_PASS', getenv('DB_PASS') ?: '');              // Пароль MySQL
```

### 4. Запуск проекта

#### Вариант 1: Встроенный PHP сервер (для разработки)

```bash
cd auth-website
php -S localhost:8000
```

Откройте в браузере: `http://localhost:8000`

#### Вариант 2: Apache/Nginx

1. Скопируйте файлы проекта в директорию веб-сервера:
   - Apache: `/var/www/html/auth-website/` или `htdocs/auth-website/`
   - Nginx: настройте server block для указания на директорию проекта

2. Откройте в браузере: `http://localhost/auth-website/`

## 🔐 Безопасность

Проект реализует следующие меры безопасности:

- **Хэширование паролей** - используется `password_hash()` с алгоритмом PASSWORD_DEFAULT
- **Prepared Statements** - все SQL запросы используют подготовленные выражения PDO
- **Санитизация данных** - входные данные обрабатываются функцией `htmlspecialchars()`
- **Валидация** - проверка данных на клиентской и серверной стороне
- **PHP Sessions** - безопасное управление сессиями пользователей

## 📱 Адаптивность

Сайт полностью адаптивен и корректно отображается на:
- Десктопах
- Планшетах
- Мобильных устройствах

## 🎨 Дизайн

- Современный градиентный фон
- Чистые и понятные формы
- Интуитивная навигация
- Визуальная обратная связь (ошибки, успех)

## 📝 API Endpoints

| Файл | Метод | Описание |
|------|-------|----------|
| `login.php` | GET | Отображение формы входа |
| `auth.php` | POST | Обработка авторизации |
| `register.php` | GET | Отображение формы регистрации |
| `register_handler.php` | POST | Обработка регистрации |
| `index.php` | GET | Главная страница (требует авторизации) |
| `logout.php` | GET | Выход из системы |

## ⚠️ Примечание

Этот проект создан в учебных целях. Для production-использования рекомендуется:

- Добавить CSRF-защиту
- Настроить HTTPS
- Добавить ограничение попыток входа (rate limiting)
- Реализовать подтверждение email
- Добавить функцию восстановления пароля

## 📄 Лицензия

MIT License