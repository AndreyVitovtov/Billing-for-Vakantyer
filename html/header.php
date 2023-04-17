<?php use Model\Balance;

require_once 'connections.php'; ?>

<!doctype html>
<html lang="en">
<head>
    <title>Header</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<a href="#" class="button-balance">
    Balance: <?= (new Balance($userId ?? 1))->getFloat() ?> AZN
</a>
<a href="#" class="button-balance">
    Add +
</a>
</body>
</html>