<?php

require_once 'authentication.php';

if (!isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Cart</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="public/css/default.css"/>
    <link type="text/css" rel="stylesheet" href="public/css/cart.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once '_header.php' ?>
    <div id="content">

        <!-- Liste de pièces dans le panier. -->
        <div id="items">
            <div class="item" data-item_id="23">
                <img src="public/images/partTypes/123.png"/>
                <span>Reclining Handle</span> -
                <span>13453432675</span>
                <input type="text" value="2"/>
                <a class="addCart"></a>
                <a class="removeAdd"></a>
            </div>
        </div>
        <!-- Fin -->

    </div>
    <?php include_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="public/scripts/cart.js"></script>
<!-- Fin -->
</body>
</html>