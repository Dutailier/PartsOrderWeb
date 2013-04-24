<?php require_once 'protected/authentication.php' ?>

<html>
<head>
    <title>BabiesRus Parts Order - Cart</title>

    <!-- Feuilles de style. -->
    <style><?php require_once 'public/css/default.css' ?></style>
    <style><?php require_once 'public/css/cart.css' ?></style>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php require_once '_header.php' ?>
    <div id="content">

        <!-- Liste de pièces dans le panier. -->
        <div id="items">
        </div>
        <!-- Fin -->

    </div>
    <?php require_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script><?php require_once 'public/scripts/cart.js' ?></script>
<!-- Fin -->
</body>
</html>