<?php
//Функция форматирования суммы на разряды.
function formatPrice(float $num): string
{
    $price = ceil($num);
    $newFormat = number_format($price, 0, '.', ' ');
    $ruble_sign = '&#x20bd';
    $price = ($price < 1000) ? $price : $newFormat;
    return $price . ' ' . $ruble_sign;
}

//Функция проверки и очистки данных, введенных пользователем.
function checkUserData(string $str): string {
    return htmlspecialchars(strip_tags($str));
}

//Функция расчета времени до конца торгов
date_default_timezone_set('Europe/Moscow');

function countExpiryTime(string $date){
    $expiry_date = date_create($date);
    $now = date_create(date('Y-m-d H:i:s'));

    if($expiry_date > $now) {
        $diff = (array) date_diff($now, $expiry_date);
        $hours_in_day = 24;
        $hours_left = $diff['d'] * $hours_in_day + $diff['h'];
        $hours_left = ($hours_left >= 10) ? ('' . $hours_left) : ('0' . $hours_left);
        $minutes_left = ($diff['i'] >= 10) ? ('' . $diff['i']) : ('0' . $diff['i']);
        $time_left =  [$hours_left, $minutes_left];
    } else {
        $time_left = ['00', '00'];
    }

    return $time_left;
}
