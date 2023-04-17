<?php

require_once 'vendor/autoload.php';

use Model\Balance;
use Model\BalancePackage;

const _JEXEC = 1;
if (file_exists(__DIR__ . '/defines.php')) include_once __DIR__ . '/defines.php';
if (!defined('_JDEFINES')) {
    define('JPATH_BASE', __DIR__);
    require_once JPATH_BASE . '/includes/defines.php';
}
require_once JPATH_BASE . '/includes/framework.php';
$app = JFactory::getApplication('site');
$userId = JFactory::getUser()->id;

$balancePackage = (new BalancePackage($userId))->get();
$balance = new Balance($userId);
$activeAdd = ($balancePackage && $balancePackage->check() && $balance->getFloat() >= $balancePackage->price);
?>

<style>
   * {
        font-family: Arial, sans-serif;
        box-sizing: border-box;
    }

    a {
        text-decoration: none;
        cursor: pointer;
    }

    .button-balance {
        border: solid 2px #434343;
        background-color: #434343;
        border-radius: 15px;
        color: #fff;
        padding: 8px 10px;
    }

    .button-balance.active, .button-balance.balance {
        text-decoration: none;
        border: solid 2px #0c1e70;
        background-color: #0c1e70;
        border-radius: 15px;
        color: #fff;
        padding: 8px 10px;
        margin: 5px;
    }

    .button-balance.active:hover {
        background-color: #fff;
        color: #0c1e70;
    }
</style>

<a href="#" class="button-balance balance">
    Баланс: <?= $balance->getFloat() ?> AZN
</a>
<a <?= $activeAdd ? 'href="https://vakantyer.az/axtar%C4%B1%C5%9F/post_ad"' : '' ?>
   class="button-balance <?= $activeAdd ? 'active' : '' ?>">
    Добавить +
</a>
