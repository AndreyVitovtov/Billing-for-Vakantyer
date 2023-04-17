<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use Model\Order;
use Model\Package;
use Model\Payriff;

require_once '../vendor/autoload.php';

if (!isset($_REQUEST['package']) || !isset($_REQUEST['id'])) {
    die('Failed. Required parameters not passed');
}

$packageId = $_REQUEST['package'];
$userId = $_REQUEST['id'];

$package = (new Package($packageId))->get();

if (!$package) die('Package not found');
if ($package->free) die('For the selected package, payment is not provided');

$payriff = new Payriff();
$order = new Order($userId);
$hash = $order->createHash();
$response = $payriff->createOrder(
    $package->price,
    PAYRIFF_DESCRIPTION,
    $userId,
    $hash
);

if (isset($response->payload)) {
    $order->setResponse($response);
    try {
        $order->add($hash, $package->id);
    } catch (Exception $e) {
        die($e->getMessage());
    }

    $paymentUrl = $response->payload->paymentUrl ?? '';

    if (!empty($paymentUrl)) header('Location: ' . $paymentUrl);
}
echo 'Something went wrong, please try again later.';