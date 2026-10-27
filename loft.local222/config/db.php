<?php
$host = 'MySQL-8.4'; // Сменили с localhost
$db   = 'loft_site';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
return [
    'admin_password_hash' => '$2y$12$r32WCBv47s8UgAXfbMD.veuKmARiAZfpj7llPDTBz9G3.Tw1H5rgO',
    // ... остальные параметры базы данных, если нужно
];