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


//(new Mail())->sendMessage(
//    'andrey.vitovtov@gmail.com',
//    'Test',
//    'Test message'
//);


$pay = new Payriff();
//echo json_encode($pay->createOrder(100, 'Test'));
echo json_encode($pay->getStatusOrder('535697', 'F60A04F7914E70F9ED789359E0CABACF'));
