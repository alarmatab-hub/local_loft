<?php
$routes = [
    '/' => ['file' => 'pages/home.php', 'title' => 'Главная'],
    'catalog' => ['file' => 'pages/catalog.php', 'title' => 'Каталог'],
    'menu' => ['file' => 'pages/menu.php', 'title' => 'Наше меню'],
    'services' => ['file' => 'pages/services.php', 'title' => 'Наши услуги'],
    'contacts' => ['file' => 'pages/contacts.php', 'title' => 'Контакты'],
    'gallery' => ['file' => 'pages/gallery.php', 'title' => 'Галерея'],
    'cart' => ['file' => 'pages/cart.php', 'title' => 'Бланк заказа'],
    'login' => ['file' => 'pages/login.php', 'title' => 'Вход'],
    'admin/work_table' => ['file' => 'pages/work_table.php', 'title' => 'Админ-панель: Товары'],
    'admin/auth/login' => ['file' => 'admin/auth/login.php', 'title' => 'Вход в админку'],
    'orders' => ['file' => 'pages/orders_table.php', 'title' => 'Таблица заказов'],
    'welcome' => ['file' => 'pages/welcome.php', 'title' => 'Профиль'],
    'auth/register' => ['file' => 'auth/register.php', 'title' => 'Регистрация...'],
    'profile/edit' => ['file' => 'pages/profile_edit.php', 'title' => 'Редактирование профиля'],
];
$route = $_GET['route'] ?? '/';