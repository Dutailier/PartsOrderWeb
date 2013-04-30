<?php

include_once('config.php');
include_once(ROOT . 'libs/account.php');

?>

<div id="header-band">
    <div id="header-wrapper">
        <img id="logo-dutailier" src="img/dutailier.png">
        <ul id="menu">

            <?php if (basename($_SERVER['PHP_SELF']) != 'index.php') : ?>
                <li><a href="catalog.php">Home</a></li>
            <?php endif; ?>

            <?php if (Account::isAuthenticated()) : ?>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
        <img id="logo-babiesRus" src="img/babiesrus.png">
    </div>
</div>