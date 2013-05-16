<?php

include_once('config.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Parts</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/default.css"/>
    <link type="text/css" rel="stylesheet" href="css/cart.css"/>
    <link type="text/css" rel="stylesheet" href="css/buttons.css"/>
    <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <!-- Fin -->

</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <h1>Parts ordered</h1>

        <!-- Liste des items. -->
        <div id="items">
        </div>
        <!-- Fin -->

        <!-- Sommaire des pièces commandées -->
        <form id="summary">
            <input id="btnOrder" name="btnOrder" type="button" value="order"/>
            <a id="btnClear" class="button">clear</a>
        </form>
        <!-- Fin -->

        <div id="dialog" title="Confirmation required">
            Are you sure you want clear your shopping cart?
        </div>
    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/cart.js"></script>
<!-- Fin -->

</body>
</html>