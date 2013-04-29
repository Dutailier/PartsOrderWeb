<?php require_once(dirname(__FILE__) . '/authentication.php'); ?>

<div id="header-band">
    <div id="header-wrapper">
        <img id="logo-dutailier" src="public/images/dutailier.png">
        <ul id="menu">

            <?php if (basename($_SERVER['PHP_SELF']) != 'index.php') : ?>
                <li><a href="categories.php">Home</a></li>
            <?php endif; ?>

            <?php if (isAuthenticated()) : ?>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="logout.php">Log out</a></li>
            <?php endif; ?>
        </ul>
        <img id="logo-babiesRus" src="public/images/babiesrus.png">
    </div>
</div>