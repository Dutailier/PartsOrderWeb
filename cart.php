<?php

require_once 'protected/authentication.php';

if (!isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Cart</title>

    <!-- Feuilles de style. -->
    <link rel="stylesheet" type="text/css" href="public/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="public/css/cart.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php require_once '_header.php' ?>
    <div id="content">

        <!-- Liste de pièces dans le panier. -->
        <div id="items">
            <div class="item" data-item_id="23">
                <img src="public/images/partTypes/.png"/>
                <span>Reclining Handle</span> -
                <span>13453432675</span>
                <label for="quantity">Qty</label>
                <input id="quantity" type="text" value="2"/>
                <a class="addCart"></a>
                <a class="removeAdd"></a>
            </div>
        </div>
        <!-- Fin -->

    </div>
    <?php require_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="public/scripts/jquery.min.js"></script>
<script src="public/scripts/cart.js"></script>
<!-- Fin -->
</body>
</html>