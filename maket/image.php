<?php

/*
 * (C) Svetozar Volkov <swetozar.volkov@ya.ru>
 */

//если нужно вывести изображение в браузер
header('Content-type: image/png');

//создание изображения
$img = imagecreate(width, height);

//толщина линии
imagesetthickness($img, px);

//первый вызов - цвет фона, последующие - цвет
$bg = imagecolorallocate($img, 255, 255, 255);

//рисование линии
imageline($img, x1, y1, x2, y2, color);

// Текст, используя шрифт TrueType - НУЖЕН ФАЙЛ СО ШРИФТОМ
//х,у - координаты левого нижнего угла
imagettftext($img, size, angle, x, y, color, font, text);

//вывод изображения в стандартный вывод (браузер) или в файл
imagepng($img, file_null);

//освобождения памяти, выделенной под изображение
imagedestroy($img);

//Возвращаем цвет пикселя с координатами (10, 15) на изображении $image
$color = imagecolorat($image, 10, 15);
//Получаем составляющие цвета (red, green, blue)
$r = ($color >> 16) & 0xFF;
$g = ($color >> 8) & 0xFF;
$b = $color & 0xFF;
//Выводим результат
echo $r."<br />";
echo $g."<br />";
echo $b."<br />";
