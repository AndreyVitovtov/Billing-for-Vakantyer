<?php

use DB\Database;
use Model\Balance;
use Model\BalancePackage;
use Model\Mail;
use Model\Package;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

$userId = $_REQUEST['userId'];
$packageId = $_REQUEST['package'];

$db = Database::instance()->getDbh();
try {
    $db->beginTransaction();

    $package = (new Package($packageId))->get();

    if (!$package) die('Package not found');
    if ($package->free) die('For the selected package, payment is not provided');

    $balancePackage = (new BalancePackage(
        $userId,
        $package->vacancyCost,
        0,
        $package->price,
        $package->id,
        date('Y-m-d H:i:s', strtotime('+ 10 YEARS', time())),
        'bank',
        null,
        'pending',
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ))->add();

    $balance = (new Balance($userId))->add($balancePackage->price);

    $db->commit();
} catch (PDOException $ex) {
    $db->rollBack();
    die('Failed to top up balance');
}

//(new Mail())->sendMessage(
//    'user@email.com',
//    'Replenishment',
//    'Account topped up on ' . $balancePackage->price
//);

header('Location: ' . REDIRECT_AFTER_PAYMENT);