<?php

include_once('config.php');
include_once(ROOT . 'libs/user.php');

if (!User::isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Parts</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/default.css"/>
    <link type="text/css" rel="stylesheet" href="css/cart.css"/>
    <link type="text/css" rel="stylesheet" href="css/shoppingButtons.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <h1>Parts ordered</h1>

        <!-- Liste des items. -->
        <div id="parts">
        </div>
        <!-- Fin -->

        <!-- Sommaire des pièces commandées -->
        <form id="summary">
            <input id="btnOrder" name="btnOrder" type="submit" value="order"/>
            <a id="btnClear">clear</a>
        </form>
        <!-- Fin -->

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="js/cart.js"></script>
<!-- Fin -->
</body>
</html>