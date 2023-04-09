<?php

use Model\Balance;
use Model\BalancePackage;
use Model\Package;
use Model\Payriff;

require_once 'vendor/autoload.php';

//$bp = new BalancePackage(1, 10, 0, 100, 1);
//var_dump($bp->add());

$bp = new BalancePackage(1);
var_dump($bp->get());

