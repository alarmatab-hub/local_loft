<?php
// Создаёт logo_base.png — копию logo.png со стёртой шестернён (область под линией).
// Исходный logo.png остаётся нетронутым.
$src = __DIR__ . '/public/assets/images/logo.png';
$dst = __DIR__ . '/public/assets/images/logo_base.png';

$im = imagecreatefrompng($src);
imagealphablending($im, false); // писать пиксели напрямую, с альфой
imagesavealpha($im, true);

$transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);

// Шестерня в source-координатах (1260x832): центр ~ (652, 692), радиус ~125.
// Стираем прямоугольник строго НИЖЕ горизонтальной линии (~y=688), чтобы линию не задеть.
$x0 = 410; $x1 = 895;
$y0 = 689; $y1 = 831;

$erased = 0;
for ($y = $y0; $y <= $y1; $y++) {
    for ($x = $x0; $x <= $x1; $x++) {
        $c = imagecolorat($im, $x, $y);
        $al = ($c >> 24) & 0x7F;
        if ($al < 120) { // любой непустой (золотой/полупрозрачный) пиксель шестерни
            imagesetpixel($im, $x, $y, $transparent);
            $erased++;
        }
    }
}

imagepng($im, $dst);
echo "wrote {$dst} (erased {$erased} px)\n";
