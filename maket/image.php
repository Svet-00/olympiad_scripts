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

//вывод изображения в стандартный вывод (браузер) или в файл
imagepng($img, file_null);

//освобождения памяти, выделенной под изображение
imagedestroy($img);
