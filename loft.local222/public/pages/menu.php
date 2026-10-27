<?php
require_once __DIR__ . '/../../config/db.php';
global $pdo;

$cat_id = $_GET['category_id'] ?? null;

/** @noinspection SqlResolve */
/** @noinspection SqlNoDataSourceInspection */
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$search = $_GET['search'] ?? null;
$where = "WHERE p.status = 1";
if ($cat_id) $where .= " AND p.category_id = " . (int)$cat_id;
if ($search) $where .= " AND p.name LIKE " . $pdo->quote('%' . $search . '%');

$sql = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id $where";
$products = $pdo->query($sql)->fetchAll();
$allProducts = $pdo->query("SELECT id, category_id FROM products")->fetchAll();
$productCategoryMap = array_column($allProducts, 'category_id', 'id');

function renderCategoryList($categories, $cat_id) {
    // «ПОВАРЕННАЯ КНИГА» = все товары (без фильтра по категории)
    echo '<a href="?page=menu" class="cat-link cookbook-link ' . (!$cat_id ? 'active' : '') . '">ПОВАРЕННАЯ КНИГА</a>';
    foreach ($categories as $cat) {
        $active = ($cat_id == $cat['id']) ? 'active' : '';
        echo '<a href="?page=menu&amp;category_id=' . (int)$cat['id'] . '" class="cat-link ' . $active . '">' . mb_strtoupper(htmlspecialchars($cat['name'])) . '</a>';
    }
}

/* Разделение тысяч в цене узкими пробелами: 12000 -> 12 000 */
function formatPrice($value) {
    return number_format((int)$value, 0, '', "\u{2009}");
}
?>

<div class="sidebar-wrap">
    <aside class="sidebar">
        <div id="sidebar-scroll" class="marquee-content">
            <?php
            renderCategoryList($categories, $cat_id);
            renderCategoryList($categories, $cat_id);
            renderCategoryList($categories, $cat_id);
            renderCategoryList($categories, $cat_id);
            ?>
        </div>
    </aside>
</div>

<main class="container catalog-page">
    <div class="content">
        <div class="catalog-grid">
            <?php if ($products): ?>
                <?php foreach ($products as $product): ?>
                    <?php
                    // Цена со скидкой и размер скидки в рублях — для стикера, итогов
                    // карточки и корзины (итог считается от цены со скидкой).
                    // В строке цены показывается исходная цена без скидки.
                    $discountedPrice = (int)($product['price'] - ($product['price'] * ($product['discount'] / 100)));
                    $discountSum = (int)($product['price'] - $discountedPrice);
                    ?>
                    <div class="catalog-item" id="product-<?= $product['id'] ?>">
                        <div class="img-wrapper">
                            <span class="cat-label"><?= htmlspecialchars($product['category_name']) ?></span>
                            <?php if ($product['discount'] > 0): ?>
                                <!-- Товар не в корзине — стикер показывает только процент;
                                     сумму скидки на всё количество подставляет JS при добавлении
                                     (см. updateProductTotals, база — data-атрибуты) -->
                                <span class="discount-label" data-discount="<?= (int)$product['discount'] ?>" data-discount-sum="<?= $discountSum ?>">-<?= (int)$product['discount'] ?>%</span>
                            <?php endif; ?>
                            <img class="product-img" src="<?= !empty($product['image_path']) ? '/images/item/' . htmlspecialchars($product['image_path']) : '/assets/images/items/images.jpg' ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <button class="info-btn" onclick='showProductDetail(<?= json_encode($product, ENT_QUOTES) ?>)'></button>
                            <input type="number" value="0" min="0" class="qty-input" aria-label="Количество" data-id="<?= $product['id'] ?>"
                                   onfocus="this.select()"
                                   oninput="processUpdate(this, '<?= $product['id'] ?>', <?= $discountedPrice ?>, '<?= htmlspecialchars($product['name']) ?>', <?= (int)$product['weight'] ?>);">
                            <div class="cart-controls">
                                <a href="javascript:void(0)" class="btn-cta" data-id="<?= $product['id'] ?>" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>" data-discount="<?= (int)$product['discount'] ?>" data-weight="<?= (int)$product['weight'] ?>" data-category="<?= htmlspecialchars($product['category_name']) ?>"></a>
                            </div>
                        </div>
                        <div class="product-details">
                            <h3><span><?= htmlspecialchars($product['name']) ?></span></h3>
                            <div class="detail-line">
                                <span class="weight"><?= (int)$product['weight'] ?>&thinsp;гр</span>
                                <span class="total-weight" data-base-weight="<?= (int)$product['weight'] ?>"></span>
                            </div>
                            <div class="detail-line">
                                <!-- Со скидкой: зачёркнутая основная цена + рядом цена со скидкой;
                                     итог справа (data-base-price) — со скидкой -->
                                <strong class="price">
                                    <?php if ($product['discount'] > 0): ?>
                                        <span class="price-old"><?= formatPrice($product['price']) ?>&thinsp;₽</span>
                                        <span class="price-new"><?= formatPrice($discountedPrice) ?>&thinsp;₽</span>
                                    <?php else: ?>
                                        <?= formatPrice($product['price']) ?>&thinsp;₽
                                    <?php endif; ?>
                                </strong>
                                <strong class="total-price" data-base-price="<?= $discountedPrice ?>"></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: 1 / -1; text-align: center; width: 100%; margin-top: 50px;">
                    <p>Данных по этому запросу не найдено.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Модальные окна -->
<div id="product-detail-modal" class="modal-overlay" onclick="if(event.target === this) this.style.display='none'">
    <div class="modal-content" style="max-width: 80vw; max-height: 80vh; overflow-y: auto; text-align: left;">
        <div id="product-detail-body"></div>
        <button onclick="document.getElementById('product-detail-modal').style.display='none'" style="width: 100%; border: 2px solid var(--primary); background: var(--bg-dark); color: var(--text-light); padding: 15px 30px; border-radius: 6px; cursor: pointer; font-size: 1.2rem; font-weight: bold; text-transform: uppercase; margin-top: 15px;">Закрыть</button>
    </div>
</div>

<div id="confirm-modal" class="modal-overlay">
    <div class="modal-content modal-dialog">
        <p class="modal-title">Удалить товар из корзины?</p>
        <div class="modal-actions">
            <button class="modal-btn confirm" onclick="handleDelete(true)">Да</button>
            <button class="modal-btn" onclick="handleDelete(false)">Нет</button>
        </div>
    </div>
</div>

<div id="warning-modal" class="modal-overlay">
    <div class="modal-content modal-dialog">
        <p class="modal-title">Максимум — 9999</p>
        <div class="modal-actions">
            <button class="modal-btn confirm" onclick="document.getElementById('warning-modal').style.display='none'">ОК</button>
        </div>
    </div>
</div>

<script>
    function showProductDetail(product) {
        const body = document.getElementById('product-detail-body');
        const img = (product.image_path && product.image_path !== '') ? `/images/item/${product.image_path}` : '/assets/images/items/images.jpg';
        body.innerHTML = `<div class="modal-grid" style="display: flex; gap: 20px;"><div style="flex: 1;"><img src="${img}" alt="${product.name}" style="width:100%; max-height: 60vh; object-fit: contain; border-radius: 8px;"></div><div style="flex: 1;"><h2>${product.name}</h2><p><strong>Категория:</strong> ${product.category_name || 'Без категории'}</p><p><strong>Цена:</strong> ${product.price}₽</p><p><strong>Вес:</strong> ${product.weight}гр</p><p>${product.description || 'Описание отсутствует.'}</p></div></div>`;
        document.getElementById('product-detail-modal').style.display = 'flex';
    }

    function highlightProductFromHash() {
        const hash = window.location.hash;
        if (hash && hash.startsWith('#product-')) {
            const element = document.querySelector(hash);
            if (element) {
                element.classList.add('highlight');
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => element.classList.remove('highlight'), 3000);
            }
        }
    }

    // Разделитель тысяч — тонкий пробел (типографский стандарт, как в PHP formatPrice)
    function formatNum(n) {
        return n.toLocaleString('ru-RU').replace(/[\u00A0\s]/g, '\u2009');
    }

    function updateProductTotals(input) {
        const container = input.closest('.catalog-item');
        const qty = parseInt(input.value) || 0;
        // Кнопка «добавить»: золотой цвет только если товар в корзине (qty >= 1)
        const addBtn = container.querySelector('.btn-cta');
        if (addBtn) addBtn.classList.toggle('added', qty >= 1);
        // Товар добавлен — золотая рамка вокруг картинки (класс на карточке)
        container.classList.toggle('added', qty >= 1);
        const totalWeight = container.querySelector('.total-weight');
        const totalPrice = container.querySelector('.total-price');
        // Стикер скидки: пока товар НЕ в корзине — только процент (сумма не показывается).
        // При добавлении — дописывается сумма скидки на всё количество (на единицу × qty).
        const discLabel = container.querySelector('.discount-label');
        if (discLabel) {
            const pct = parseInt(discLabel.getAttribute('data-discount')) || 0;
            const unitSum = parseInt(discLabel.getAttribute('data-discount-sum')) || 0;
            discLabel.textContent = qty >= 1
                ? '-' + pct + '% = ' + formatNum(unitSum * qty) + '\u2009\u20BD'
                : '-' + pct + '%';
        }
        if (qty >= 1) {
            // итог справа от веса (в кг) и напротив цены
            totalWeight.textContent = '= ' + (parseInt(totalWeight.getAttribute('data-base-weight')) * qty / 1000).toFixed(2) + '\u2009кг';
            totalPrice.textContent = '= ' + formatNum(parseFloat(totalPrice.getAttribute('data-base-price')) * qty) + '\u2009₽';
        } else {
            totalWeight.textContent = '';
            totalPrice.textContent = '';
        }
    }

    function addToCart(btn, id, name, price, weight, category, discount) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let item = cart.find(i => i.id === id);
        let discountedPrice = price - (price * (discount / 100));

        if (item) {
            if (item.quantity >= 9999) return document.getElementById('warning-modal').style.display = 'flex';
            item.quantity += 1;
        } else {
            cart.push({ id, name, price: discountedPrice, weight, quantity: 1, category: category || 'Без категории' });
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        const input = btn.closest('.catalog-item').querySelector('.qty-input');
        input.style.display = 'block';
        input.value = item ? item.quantity : 1;
        updateProductTotals(input);
    }

    let pendingDelete = null;
    function processUpdate(input, id, price, name, weight) {
        if (document.getElementById('confirm-modal').style.display === 'flex') return;
        let qty = parseInt(input.value) || 0;
        if (qty > 9999) { input.value = 9999; return document.getElementById('warning-modal').style.display = 'flex'; }
        if (qty <= 0) {
            pendingDelete = { id, input };
            document.getElementById('confirm-modal').style.display = 'flex';
        } else {
            updateCart(id, qty, price, name, weight);
            input.style.display = 'block';
            updateProductTotals(input);
        }
    }

    function updateCart(id, qty, price, name, weight) {
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        let item = cart.find(i => i.id === id);
        // Цену перезаписываем и у существующего товара — иначе в корзине
        // остаются записи со старой ценой без учёта скидки
        if (item) { item.quantity = qty; item.price = price; } else cart.push({ id, name, price, weight, quantity: qty });
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function handleDelete(confirmed) {
        if (confirmed && pendingDelete) {
            let cart = JSON.parse(localStorage.getItem('cart') || '[]');
            cart = cart.filter(i => i.id !== pendingDelete.id);
            localStorage.setItem('cart', JSON.stringify(cart));
            pendingDelete.input.style.display = 'none';
            pendingDelete.input.value = 0;
            updateProductTotals(pendingDelete.input);
        } else if (pendingDelete) {
            const item = JSON.parse(localStorage.getItem('cart') || '[]').find(i => i.id === pendingDelete.id);
            pendingDelete.input.value = item ? item.quantity : 1;
        }
        document.getElementById('confirm-modal').style.display = 'none';
        pendingDelete = null;
    }

    function initCartLogic() {
        const cart = JSON.parse(localStorage.getItem('cart') || '[]');
        document.querySelectorAll('.qty-input').forEach(input => {
            const item = cart.find(i => i.id === input.getAttribute('data-id'));
            if (item) { input.value = item.quantity; input.style.display = 'block'; updateProductTotals(input); }
        });
        document.querySelectorAll('.btn-cta').forEach(btn => {
            btn.addEventListener('click', () => addToCart(btn, btn.dataset.id, btn.dataset.name, parseFloat(btn.dataset.price), parseFloat(btn.dataset.weight), btn.dataset.category, parseFloat(btn.dataset.discount || 0)));
        });
    }

    // Сайдбар: автопрокрутка через scrollLeft + ручная прокрутка (колесо мыши, тач/трэкпад — нативно).
    // Перетаскивание и выделение/копирование текста отключены; автопрокрутка продолжается после окончания взаимодействия.
    function initSidebarMarquee() {
        const sidebar = document.querySelector('.sidebar');
        const content = sidebar && sidebar.querySelector('.marquee-content');
        if (!sidebar || !content) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let userActive = false, idleTimer = null;
        const halfWidth = () => content.scrollWidth / 2;

        // Пользователь взаимодействует с сайдбаром — ставим автопрокрутку на паузу
        function markActive() {
            userActive = true;
            clearTimeout(idleTimer);
            // Взаимодействие окончено (нет событий ~1.2 c) — возобновляем прокрутку
            idleTimer = setTimeout(() => { userActive = false; }, 1200);
        }

        let lastAutoTs = 0;
        // scrollLeft округляется до целого пикселя, поэтому дробный шаг копим
        // в аккумуляторе и прибавляем к прокрутке только целиком пиксели (≥1).
        const AUTO_SPEED = 0.5; // px за кадр (~30px/с при 60Гц) — вдвое медленнее прежних ~60px/с
        let autoAccum = 0;

        // Прокрутка происходит (колесо, трэкпад, тач) — обновляем отметку активности
        sidebar.addEventListener('scroll', () => {
            if (reduceMotion) return;
            // события scroll от автопрокрутки приходят сразу после шага — игнорируем их
            if (performance.now() - lastAutoTs > 100) markActive();
        }, { passive: true });

        sidebar.addEventListener('touchstart', markActive, { passive: true });

        function step() {
            if (!userActive && !reduceMotion) {
                lastAutoTs = performance.now();
                autoAccum += AUTO_SPEED;
                if (autoAccum >= 1) {
                    const px = Math.floor(autoAccum);
                    autoAccum -= px;
                    sidebar.scrollLeft += px;
                    const h = halfWidth();
                    // разметка повторена → визуал в x идентичен x-h, перенос бесшовный
                    if (h > 0 && sidebar.scrollLeft >= h) sidebar.scrollLeft -= h;
                }
            }
            requestAnimationFrame(step);
        }
        if (!reduceMotion) requestAnimationFrame(step);

        // Колесо мыши: вертикальный скролл крутится сайдбаром;
        // на краях диапазона событие отдаётся странице, чтобы не блокировать прокрутку сайта
        sidebar.addEventListener('wheel', (e) => {
            markActive();
            const delta = Math.abs(e.deltaY) > Math.abs(e.deltaX) ? e.deltaY : e.deltaX;
            const max = sidebar.scrollWidth - sidebar.clientWidth;
            if (max <= 0) return;
            const atStart = sidebar.scrollLeft <= 0 && delta < 0;
            const atEnd = sidebar.scrollLeft >= max && delta > 0;
            if (atStart || atEnd) return; // дальше крутим страницу
            e.preventDefault();
            sidebar.scrollLeft += delta;
        }, { passive: false });

        // Блокируем выделение и нативное перетаскивание текста/картинок внутри сайдбара
        sidebar.addEventListener('dragstart', (e) => e.preventDefault());
        sidebar.addEventListener('selectstart', (e) => e.preventDefault());
    }

    document.addEventListener('DOMContentLoaded', () => {
        initCartLogic();
        highlightProductFromHash();
        initSidebarMarquee();

        document.querySelectorAll('.cat-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('href');
                // Подсвечиваем активный пункт во всех копиях карусели
                document.querySelectorAll('.cat-link').forEach(l => l.classList.toggle('active', l.getAttribute('href') === url));
                fetch(url).then(r => r.text()).then(html => {
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    document.querySelector('.catalog-grid').innerHTML = doc.querySelector('.catalog-grid').innerHTML;
                    window.history.pushState({}, '', url);
                    initCartLogic();
                    // Переход к первому товару выбранной категории: прокрутка вверх
                    // с отступом на липкие шапку и сайдбар категорий, чтобы карточка
                    // не пряталась под ними.
                    const firstItem = document.querySelector('.catalog-grid .catalog-item');
                    if (firstItem) {
                        const wrap = document.querySelector('.sidebar-wrap');
                        const offset = wrap ? wrap.getBoundingClientRect().bottom + 12 : 160;
                        const top = firstItem.getBoundingClientRect().top + window.scrollY - offset;
                        window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
                    }
                });
            });
        });
    });
</script>