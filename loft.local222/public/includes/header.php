<div class="top-bar">
    <form class="search-form" action="?page=menu" method="get" role="search">
        <input type="text" name="search" class="search-input" placeholder="Поиск по каталогу..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit" class="search-btn" aria-label="Найти"></button>
    </form>
</div>
<header>
    <div class="nav-container">
        <a href="/" class="logo">
            <div class="sparks-container" id="sparks"></div>
            <div class="sparks-container" id="red-sparks"></div> <!-- Новый контейнер -->
            <img src="assets/images/logo.png" alt="Logo">
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a href="?page=home" class="<?= ($page ?? 'home') === 'home' ? 'active' : '' ?>">Главная</a></li>
            <li><a href="?page=about" class="<?= ($page ?? 'home') === 'about' ? 'active' : '' ?>">О нас</a></li>
            <li><a href="?page=catalog" class="<?= in_array($page ?? 'home', ['catalog', 'menu'], true) ? 'active' : '' ?>">Каталог</a></li>
            <li><a href="?page=contacts" class="<?= ($page ?? 'home') === 'contacts' ? 'active' : '' ?>">Контакты</a></li>
        </ul>
        <div class="burger" id="burger">
            <span></span><span></span><span></span>
        </div>
    </div>
</header>
