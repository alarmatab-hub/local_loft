    let yellowSparkInterval = null;
    let redSparkInterval = null;
    let scrollTimeout = null;

    // Горизонтальный дрейф фигур в шапке: копим смещение по направлению
    // вертикального скролла (вниз — ряд уезжает влево, вверх — вправо)
    let lastScrollY = window.scrollY;
    const PILL_DRIFT_MAX = 240; // px, потолок смещения в каждую сторону (выше — длиннее прокрутка ряда)

    function createYellowSparks() {
    const container = document.getElementById('sparks');
    if (container) {
    const spark = document.createElement('div');
    spark.className = 'spark';
    spark.style.left = Math.random() * 100 + '%';
    container.appendChild(spark);
    setTimeout(() => spark.remove(), 1000);
}
}

    function createRedSparks() {
    const container = document.getElementById('red-sparks');
    if (container && Math.random() > 0.5) {
    const spark = document.createElement('div');
    spark.className = 'spark red';
    spark.style.left = Math.random() * 100 + '%';
    container.appendChild(spark);
    setTimeout(() => spark.remove(), 1000);
}
}

    window.addEventListener('scroll', () => {
    const header = document.querySelector('header');
    const isScrolled = window.scrollY > 10;

    // Направление/сила скролла -> дрейф ряда фигур (CSS плавно догоняет его transition)
    const delta = window.scrollY - lastScrollY;
    lastScrollY = window.scrollY;
    if (window.scrollY <= 2) {
        header.style.setProperty('--pill-drift', '0px'); // вернулись наверх — ряд на месте
    } else if (delta !== 0) {
        const current = parseFloat(header.style.getPropertyValue('--pill-drift')) || 0;
        const next = Math.max(-PILL_DRIFT_MAX, Math.min(PILL_DRIFT_MAX, current - delta * 0.15));
        header.style.setProperty('--pill-drift', next + 'px');
    }

    if (isScrolled) {
    header.classList.add('scrolled');
} else {
    header.classList.remove('scrolled');
}

    // То же состояние прокрутки зеркалим на <body>, чтобы синхронно с красной
    // подсветкой шапки включать золотое свечение под сайдбаром категорий (страница меню)
    document.body.classList.toggle('scrolled', isScrolled);

    // Красные искры: всегда при скролле > 10
    if (isScrolled && !redSparkInterval) {
    redSparkInterval = setInterval(createRedSparks, 70);
} else if (!isScrolled) {
    clearInterval(redSparkInterval);
    redSparkInterval = null;
}

    // Желтые искры: только при активном скролле
    if (!yellowSparkInterval) {
    yellowSparkInterval = setInterval(createYellowSparks, 40);
}

    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
    clearInterval(yellowSparkInterval);
    yellowSparkInterval = null;
}, 150);
});

    document.getElementById('burger').addEventListener('click', function() {
    this.classList.toggle('active');
    document.getElementById('navLinks').classList.toggle('active');
});

    // Клик ВНЕ бургера и открытого меню — закрываем шторку.
    // Проверка через closest(): клик по бургеру не закроет меню сразу (иначе
    // document-обработчик сработал бы после toggle и тут же его снял бы).
    document.addEventListener('click', function(e) {
    var nav = document.getElementById('navLinks');
    var burger = document.getElementById('burger');
    if (!nav || !nav.classList.contains('active')) return;
    if (e.target.closest('#burger') || e.target.closest('#navLinks')) return;
    nav.classList.remove('active');
    burger.classList.remove('active');
});

    // Доступность: Escape закрывает открытую шторку
    document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    var nav = document.getElementById('navLinks');
    var burger = document.getElementById('burger');
    if (nav && nav.classList.contains('active')) {
        nav.classList.remove('active');
        if (burger) burger.classList.remove('active');
    }
});
