<?php

use DB\Database;
use Model\Balance;
use Model\BalancePackage;
use Model\Order;
use Model\Package;
use Model\Payriff;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

sleep(rand(1, 5));

if (isset($_REQUEST['hash']) && isset($_REQUEST['userId'])) {
    $db = Database::instance()->getDbh();
    try {
        $db->beginTransaction();

        $userId = intval($_REQUEST['userId']);
        $hash = trim($_REQUEST['hash'], '/');

        $order = (new Order($userId))->getOrderByHash($hash);

        if (!$order || $order->complete || $order->status) header('Location: ' . REDIRECT_HOME);

        if ($order->complete) {
            die('ORDER APPROVED');
        }

        $package = (new Package($order->packageId))->get();
        $payriff = new Payriff();
        $response = $payriff->getStatusOrder($order->orderId, $order->sessionId);

        if ($response->payload->orderStatus == 'APPROVED') {
            $order->updateStatus($response->payload->orderStatus);
            $order->updateComplete(1, $order->id);

            $balance = new Balance($userId);
            $balance->add($package->price);

            // TODO: Обновить баланс пользователя в таблице users

            $balancePackage = (new BalancePackage(
                $userId,
                $package->getNumberVacancies(),
                0,
                $package->price,
                $package->id,
                date('Y-m-d H:i:s', strtotime('+ 10 YEARS', time())),
                'card',
                $order->id,
                'paid'
            ))->add();
        }

        $db->commit();
        // Redirect to ...
        header('Location: ' . REDIRECT_AFTER_PAYMENT);
    } catch (Exception $ex) {
        $db->rollBack();
        echo 'ERROR: ' . $ex->getMessage();
    }
} else {
    header('Location: ' . REDIRECT_HOME);
}