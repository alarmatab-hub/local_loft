<?php
/**
 * Домашняя страница (демо-наполнение).
 * Показывает варианты оформления и эффекты на тестовых данных.
 * Существующие классы (.hero, .content, .features-grid, .team-grid и т.д.) берутся из style.css,
 * новые демо-эффекты объявлены в scoped-блоке <style> ниже и опираются на переменные темы (:root).
 */
$stats = [
    ['label' => 'Блюд в меню', 'value' => 128, 'suffix' => '+'],
    ['label' => 'Лет на рынке', 'value' => 12, 'suffix' => ''],
    ['label' => 'Гостиц в день', 'value' => 450, 'suffix' => '+'],
    ['label' => 'Средний рейтинг', 'value' => 49, 'suffix' => '', 'display' => '4.9'],
];

$gallery = [
    ['img' => '/assets/images/ani/1.jpg', 'title' => 'Авторская подача', 'tag' => 'Кухня'],
    ['img' => '/assets/images/ani/2.jpg', 'title' => 'Атмосфера зала', 'tag' => 'Интерьер'],
    ['img' => '/assets/images/ani/3.jpg', 'title' => 'Печь и огонь', 'tag' => 'цех'],
];

$features = [
    ['icon' => '🔥', 'title' => 'Дровяная печь', 'text' => 'Готовим на открытом огне — вкус, который не повторить дома.'],
    ['icon' => '🍷', 'title' => 'Винная карта', 'text' => 'Более 60 позиций от локальных и европейских виноделен.'],
    ['icon' => '🌿', 'title' => 'Ферменные продукты', 'text' => 'Работаем напрямую с поставщиками из регионов.'],
    ['icon' => '🎧', 'title' => 'Живая музыка', 'text' => 'Каждый вечер — сеты резидентов и гостевые музыканты.'],
];

$pricing = [
    ['name' => 'Завтрак', 'price' => 'от 490', 'note' => '', 'features' => ['Кофе в подарок', 'Шведская линия', 'до 12:00'], 'featured' => false],
    ['name' => 'Обед', 'price' => 'от 890', 'note' => 'Хит', 'features' => ['Суп + горячее', 'Десерт', 'Бизнес-ланч'], 'featured' => true],
    ['name' => 'Ужин', 'price' => 'от 1490', 'note' => '', 'features' => ['Дегустация вин', 'Сет на двоих', 'Live-музыка'], 'featured' => false],
];
?>

<style>
    /* === Демо-секции на главной. Всё тематическое — через переменные из :root (style.css) === */
    .home-section { max-width: 1200px; margin: 0 auto; padding: 3rem 2rem; }
    .home-section > .section-title {
        font-size: clamp(1.6rem, 3vw, 2.4rem);
        text-align: center;
        margin-bottom: .5rem;
        background: var(--gradient);
        -webkit-background-clip: text; background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .home-section > .section-sub { text-align: center; color: var(--text-muted); margin: 0 auto 2.5rem; max-width: 640px; }

    /* Кнопки: золотая градиентная и контурная */
    .hero-actions { display: flex; gap: 1rem; margin-top: 1.8rem; flex-wrap: wrap; justify-content: center; }
    .btn {
        display: inline-flex; align-items: center; gap: .5rem;
        padding: .85rem 1.8rem; border-radius: 999px; font-weight: 700;
        font-size: .95rem; letter-spacing: .5px; text-transform: uppercase;
        cursor: pointer; border: 2px solid transparent; text-decoration: none;
        transition: transform .25s ease, box-shadow .25s ease, background .3s ease, color .3s ease;
    }
    .btn-gold { background: var(--gradient); color: #1a1a1a; }
    .btn-gold:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(212, 175, 55, .35); }
    .btn-ghost { background: transparent; border-color: var(--primary); color: var(--primary); }
    .btn-ghost:hover { background: var(--primary); color: #1a1a1a; transform: translateY(-3px); }

    /* Бегущая строка (ticker) */
    .ticker { overflow: hidden; white-space: nowrap; border-block: 1px solid var(--border); background: var(--bg-secondary); padding: .7rem 0; -webkit-mask-image: linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent); mask-image: linear-gradient(90deg,transparent,#000 8%,#000 92%,transparent); }
    .ticker-track { display: inline-flex; gap: 3rem; width: max-content; animation: ticker-move 22s linear infinite; }
    .ticker:hover .ticker-track { animation-play-state: paused; }
    .ticker span { color: var(--text-muted); font-size: .9rem; letter-spacing: 1px; text-transform: uppercase; }
    .ticker b { color: var(--primary); }
    @keyframes ticker-move { to { transform: translateX(-50%); } }

    /* Счётчики (анимация числа при появлении) */
    .stats-band { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; text-align: center; }
    .stat {
        padding: 1.6rem 1rem; border: 1px solid var(--border); border-radius: 16px; background: var(--bg-card);
        transition: transform .3s ease, border-color .3s ease;
    }
    .stat:hover { transform: translateY(-6px); border-color: var(--primary); }
    .stat-num { font-size: clamp(2rem, 5vw, 3rem); font-weight: 800; color: var(--primary); line-height: 1; }
    .stat-label { color: var(--text-muted); margin-top: .5rem; font-size: .9rem; text-transform: uppercase; letter-spacing: 1px; }

    /* Галерея с зумом и подложкой */
    .gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2rem; }
    .gallery-card { position: relative; overflow: hidden; border-radius: 16px; aspect-ratio: 4 / 3; cursor: pointer; border: 1px solid var(--border); }
    .gallery-card img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .6s cubic-bezier(.2,.8,.2,1), filter .4s ease; }
    .gallery-card:hover img { transform: scale(1.12); filter: brightness(.6); }
    .gallery-overlay { position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: flex-end; padding: 1.2rem; gap: .35rem;
        background: linear-gradient(to top, rgba(0,0,0,.75), transparent 60%); transform: translateY(15%); opacity: 0; transition: transform .4s ease, opacity .4s ease; }
    .gallery-card:hover .gallery-overlay { transform: translateY(0); opacity: 1; }
    .gallery-overlay .tag { align-self: flex-start; font-size: .7rem; text-transform: uppercase; letter-spacing: 1px; background: var(--gradient); color: #1a1a1a; padding: 3px 10px; border-radius: 999px; font-weight: 700; }
    .gallery-overlay h4 { margin: 0; color: #fff; font-size: 1.15rem; }

    /* Карточки цен: поднятие + «featured» */
    .pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.4rem; align-items: stretch; }
    .price-card { position: relative; display: flex; flex-direction: column; gap: 1rem; padding: 2rem 1.6rem; border: 1px solid var(--border); border-radius: 18px; background: var(--bg-card); transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease; }
    .price-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,.5); border-color: var(--primary); }
    .price-card.featured { border-color: var(--primary); box-shadow: inset 0 0 0 1px var(--primary); }
    .price-badge { position: absolute; top: -12px; right: 20px; background: var(--gradient); color: #1a1a1a; font-size: .7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 4px 12px; border-radius: 999px; }
    .price-card h3 { margin: 0; color: var(--text-light); font-size: 1.2rem; }
    .price-card .amount { font-size: 2.2rem; font-weight: 800; color: var(--primary); }
    .price-card ul { list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem; color: var(--text-muted); }
    .price-card li::before { content: '✓ '; color: var(--primary); font-weight: 700; }
    .price-card .btn { justify-content: center; margin-top: auto; }

    /* Цитата в стиле «матовое стекло» (glassmorphism) */
    .quote {
        position: relative; max-width: 800px; margin: 0 auto; padding: 2.5rem;
        border-radius: 20px; border: 1px solid rgba(255,255,255,.12);
        background: rgba(255,255,255,.05);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        text-align: center; overflow: hidden;
    }
    .quote::before { content: '“'; position: absolute; top: -30px; left: 18px; font-size: 10rem; line-height: 1; color: rgba(212,175,55,.18); font-family: serif; }
    .quote p { font-size: 1.25rem; color: var(--text-light); margin: 0 0 1rem; position: relative; }
    .quote cite { color: var(--primary); font-style: normal; font-weight: 700; letter-spacing: .5px; }

    /* CTA-баннер на градиентной подложке */
    .cta-banner {
        max-width: 1200px; margin: 0 auto 3rem; padding: 3rem 2rem; text-align: center; border-radius: 22px;
        background: radial-gradient(circle at 30% 20%, rgba(212,175,55,.25), transparent 45%), var(--bg-secondary);
        border: 1px solid var(--border); position: relative; overflow: hidden;
    }
    .cta-banner h2 { font-size: clamp(1.5rem, 3vw, 2.2rem); color: var(--text-light); margin: 0 0 .6rem; }
    .cta-banner p { color: var(--text-muted); margin: 0 0 1.5rem; }
    /* Пульсирующее свечение */
    .cta-banner::after { content: ''; position: absolute; inset: -40% -10% auto auto; width: 320px; height: 320px; background: radial-gradient(circle, rgba(212,175,55,.22), transparent 70%); animation: glow-pulse 4s ease-in-out infinite; }
    @keyframes glow-pulse { 0%,100% { opacity: .5; transform: scale(1); } 50% { opacity: 1; transform: scale(1.15); } }

    /* Появление секций при прокрутке */
    .reveal { opacity: 0; transform: translateY(28px); transition: opacity .7s ease, transform .7s ease; }
    .reveal.is-visible { opacity: 1; transform: none; }

    @media (max-width: 900px) {
        .stats-band { grid-template-columns: repeat(2, 1fr); }
        .gallery-grid, .pricing-grid { grid-template-columns: 1fr; }
    }
    @media (prefers-reduced-motion: reduce) {
        .ticker-track { animation: none; }
        .reveal { opacity: 1; transform: none; transition: none; }
        .cta-banner::after { animation: none; }
    }
</style>

<!-- HERO -->
<section class="hero">
    <h1>Loft — кухня на живом огне</h1>
    <p>Демонстрационная главная страница: ниже показаны различные приёмы оформления и эффекты,
        которые можно использовать на сайте — от бегущей строки до карточек цен и «стеклянных» блоков.</p>
    <div class="hero-actions">
        <a href="?page=menu" class="btn btn-gold">Смотреть меню</a>
        <a href="?page=contacts" class="btn btn-ghost">Забронировать стол</a>
    </div>
</section>

<!-- Бегущая строка -->
<div class="ticker" aria-hidden="true">
    <div class="ticker-track">
        <?php $tickerItem = '<span>🔥 <b>Скидка 20%</b> на пиццу до 15:00</span><span>🎧 Живая музыка по пятницам</span><span>🍷 Вечник сомелье</span><span>🚴 Доставка от 45 минут</span>'; ?>
        <?= $tickerItem . $tickerItem ?>
    </div>
</div>

<!-- Счётчики -->
<section class="home-section reveal">
    <h2 class="section-title">Лофт в цифрах</h2>
    <p class="section-sub">Числа плавно «докручиваются» до значения, когда блок попадает в зону видимости.</p>
    <div class="stats-band">
        <?php foreach ($stats as $s): ?>
            <div class="stat">
                <div class="stat-num" data-target="<?= $s['value'] ?>" data-suffix="<?= $s['suffix'] ?>" data-display="<?= $s['display'] ?? '' ?>">0</div>
                <div class="stat-label"><?= htmlspecialchars($s['label']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Преимущества (используем готовые .features-grid из style.css) -->
<section class="home-section reveal">
    <h2 class="section-title">Почему гости возвращаются</h2>
    <div class="features-grid">
        <?php foreach ($features as $f): ?>
            <div class="feature-card">
                <div class="feature-icon"><?= $f['icon'] ?></div>
                <h3><?= htmlspecialchars($f['title']) ?></h3>
                <p><?= htmlspecialchars($f['text']) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Галерея -->
<section class="home-section reveal">
    <h2 class="section-title">Атмосфера</h2>
    <p class="section-sub">Наведите курсор — фото плавно увеличивается и снизу выезжает подпись.</p>
    <div class="gallery-grid">
        <?php foreach ($gallery as $g): ?>
            <figure class="gallery-card" style="margin:0;">
                <img src="<?= $g['img'] ?>" alt="<?= htmlspecialchars($g['title']) ?>" loading="lazy">
                <figcaption class="gallery-overlay">
                    <span class="tag"><?= htmlspecialchars(mb_convert_case($g['tag'], MB_CASE_TITLE, 'UTF-8')) ?></span>
                    <h4><?= htmlspecialchars($g['title']) ?></h4>
                </figcaption>
            </figure>
        <?php endforeach; ?>
    </div>
</section>

<!-- Карточки цен -->
<section class="home-section reveal">
    <h2 class="section-title">Форматы визита</h2>
    <div class="pricing-grid">
        <?php foreach ($pricing as $p): ?>
            <div class="price-card<?= $p['featured'] ? ' featured' : '' ?>">
                <?php if ($p['note']): ?><span class="price-badge"><?= htmlspecialchars($p['note']) ?></span><?php endif; ?>
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <div class="amount"><?= htmlspecialchars($p['price']) ?> ₽</div>
                <ul>
                    <?php foreach ($p['features'] as $feat): ?><li><?= htmlspecialchars($feat) ?></li><?php endforeach; ?>
                </ul>
                <a href="?page=menu" class="btn <?= $p['featured'] ? 'btn-gold' : 'btn-ghost' ?>">Выбрать</a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Цитата (glassmorphism) -->
<section class="home-section reveal">
    <div class="quote">
        <p>Место, где встречают гостей с порога и не хотят отпускать. Хлеб — тот самый, огонь — живой.</p>
        <cite>— Gastro Guide, 2026</cite>
    </div>
</section>

<!-- Команда (готовые классы .team-grid из style.css) -->
<section class="home-section reveal">
    <h2 class="section-title">Команда</h2>
    <div class="team-grid">
        <?php
        $team = [
            ['a' => '👨‍🍳', 'n' => 'Артём Логинов', 'r' => 'Шеф-повар', 'd' => '15 лет кухни, стаж в Барселоне.'],
            ['a' => '🍷', 'n' => 'Мария Кольцова', 'r' => 'Сомелье', 'd' => 'Собрала карту из 60 вин.'],
            ['a' => '🍰', 'n' => 'Игорь Светлов', 'r' => 'Кондитер', 'd' => 'Авторский десерт «Уголёк».'],
            ['a' => '🎼', 'n' => 'Дана Ветрова', 'r' => 'Резидент', 'd' => 'Музыка вечера с пятницы.'],
        ];
        foreach ($team as $i => $m): ?>
            <div class="team-card" style="--i: <?= $i + 1 ?>">
                <div class="avatar"><?= $m['a'] ?></div>
                <h3><?= htmlspecialchars($m['n']) ?></h3>
                <div class="role"><?= htmlspecialchars($m['r']) ?></div>
                <div class="desc"><?= htmlspecialchars($m['d']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- CTA -->
<section class="cta-banner reveal">
    <h2>Столы на вечер разбирают быстро</h2>
    <p>Оставьте бронь за 2–3 дня — подберём зону и подготовим стол к вашему приходу.</p>
    <a href="?page=contacts" class="btn btn-gold">Забронировать стол</a>
</section>

<script>
    // Появление секций при прокрутке
    (function () {
        const reveals = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            reveals.forEach(el => el.classList.add('is-visible'));
            return;
        }
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); } });
        }, { threshold: 0.15 });
        reveals.forEach(el => io.observe(el));
    })();

    // Анимация счётчиков при попадании в зону видимости
    (function () {
        const nums = document.querySelectorAll('.stat-num');
        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const finalText = (el) => {
            const d = el.getAttribute('data-display');
            const suf = el.getAttribute('data-suffix') || '';
            el.textContent = (d ? d : parseInt(el.getAttribute('data-target'), 10)) + suf;
        };
        const animate = (el) => {
            const target = parseInt(el.getAttribute('data-target'), 10) || 0;
            const suf = el.getAttribute('data-suffix') || '';
            const dec = el.getAttribute('data-display');
            if (reduce) { finalText(el); return; }
            const dur = 1400, start = performance.now();
            const tick = (now) => {
                const p = Math.min((now - start) / dur, 1);
                const eased = 1 - Math.pow(1 - p, 3); // easeOutCubic
                if (dec) {
                    el.textContent = (target * eased / 10).toFixed(1) + suf;
                } else {
                    el.textContent = Math.round(target * eased) + suf;
                }
                if (p < 1) requestAnimationFrame(tick); else finalText(el);
            };
            requestAnimationFrame(tick);
        };
        if (!('IntersectionObserver' in window)) { nums.forEach(finalText); return; }
        const io = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { animate(e.target); io.unobserve(e.target); } });
        }, { threshold: 0.5 });
        nums.forEach(el => io.observe(el));
    })();
</script>
