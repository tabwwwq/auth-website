-- Создание базы данных
CREATE DATABASE IF NOT EXISTS auth_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE auth_website;

-- Создание таблицы пользователей
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Индексы для оптимизации поиска
CREATE INDEX idx_username ON users(username);
CREATE INDEX idx_email ON users(email);
