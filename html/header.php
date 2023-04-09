<?php use Model\Balance;

require_once 'connections.php'; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Header</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="#" class="button-balance">
    Баланс: <?= (new Balance($_SESSION['userId']))->getFloat() ?> AZN
</a>
<a href="#" class="button-balance">
    Добавить +
</a>
</body>
</html>