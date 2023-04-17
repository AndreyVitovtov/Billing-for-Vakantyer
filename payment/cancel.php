<?php

use DB\Database;
use Model\Order;
use Model\Payriff;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once '../vendor/autoload.php';

if (isset($_REQUEST['hash']) && isset($_REQUEST['userId'])) {
    $db = Database::instance()->getDbh();
    try {
        $db->beginTransaction();

        $userId = intval($_REQUEST['userId']);
        $hash = trim($_REQUEST['hash'], '/');

        $order = (new Order($userId))->getOrderByHash($hash);

        $payriff = new Payriff();
        $response = $payriff->getStatusOrder($order->orderId, $order->sessionId);

        if (isset($response->payload->orderStatus)) {
            $order->updateStatus($response->payload->orderStatus);
            $order->updateComplete(1, $order->id);
            header('Location: ' . REDIRECT_HOME);
        }
    } catch (Exception $ex) {
        echo 'ERROR: ' . $ex->getMessage();
    }
    echo 'Something went wrong, please try again later.';
} else header('Location: ' . REDIRECT_HOME);