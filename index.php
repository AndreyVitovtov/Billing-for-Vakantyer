<?php

use Model\Balance;
use Model\BalancePackage;
use Model\Mail;
use Model\Package;
use Model\Payriff;

require_once 'vendor/autoload.php';

//$bp = new BalancePackage(1, 10, 0, 100, 1, null, 'bank', null);
//var_dump($bp->add());

//$bp = new BalancePackage(1);
//var_dump($bp->getPaymentBank());

//$bp->updateStatus(1, 'paid');
//
//var_dump($bp->getPaymentBank());

//$balance = (new Balance(1))->writeOff(25);
//echo $balance->getFloat() . ' AZN';


(new Mail())->sendMessage(
    'andrey.vitovtov@gmail.com',
    'Test',
    'Test message'
);
