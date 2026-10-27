<?php
$page = $_GET['page'] ?? 'home';
$allowed_pages = ['home', 'about', 'contacts', 'catalog', 'order', 'menu'];
$page_file = "pages/{$page}.php";
if (!in_array($page, $allowed_pages, true) || !is_file(__DIR__ . "/{$page_file}")) {
    $page_file = "pages/home.php";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/style.css">
    <link rel="stylesheet" href="assets/styles/style_header.css">
    <?php if ($page === 'catalog'): ?>
        <link rel="stylesheet" href="assets/styles/style_catalog.css">
    <?php endif; ?>
    <?php if ($page === 'menu'): ?>
        <link rel="stylesheet" href="assets/styles/style_menu.css">
    <?php endif; ?>
</head>
<body class="page-<?= htmlspecialchars($page ?? 'home') ?>">
    <?php include 'includes/header.php'; ?>

    <main>
        <?php include $page_file; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
    <script src="assets/js/main.js"></script>
</body>
</html>