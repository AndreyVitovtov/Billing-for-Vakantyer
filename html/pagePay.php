<?php

use Controller\ReplenishmentOfTheBalance;
use Model\Package;

//require_once 'connections.php';
//$replenishmentOfTheBalance = new ReplenishmentOfTheBalance();
//
//// packageSelection - Используется для проверки выбранного пакета, если все ок возвращает выбранный пакет,
//// если выбранного пакета нет, переход на 404 или выводит ошибку
//// Вставить в начало страницы выбора способа оплаты пакета
//$package = $replenishmentOfTheBalance->packageSelection();
//
//// replenishmentBank - Пополнение банком, если все ок, отдает баланс, иначе выводит ошибку
//$replenishmentOfTheBalance->replenishmentBank($package);
//
//// replenishmentCard - Пополнение картой, если все ок, отдает баланс, иначе выводит ошибку
//$replenishmentOfTheBalance->replenishmentCard($package);
//
//// replenishmentFree - Пополнение бесплатно, если все ок, отдает баланс, иначе выводит ошибку
//$replenishmentOfTheBalance->replenishmentFree($package);
//

//?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PagePay</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/fontello.css">
</head>
<body>
<div class="wrapper-pay">
    <a href="https://vakantyer.az/payment/bank/<?= $userId ?? 1 ?>/<?= $_REQUEST['package'] ?>" class="type-pay bank">
        <i class="icon-bank"></i>Bank
    </a>
    <a href="https://vakantyer.az/payment/pay/<?= $userId ?? 1 ?>/<?= $_REQUEST['package'] ?>" class="type-pay bank">
        <i class="icon-credit-card"></i>Card
    </a>
</div>
</body>
</html>