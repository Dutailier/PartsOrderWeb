<?php

include_once('config.php');
include_once(ROOT . 'libs/user.php');

if (!User::isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Order</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/default.css"/>
    <link type="text/css" rel="stylesheet" href="public/css/order.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <from>
            <fieldset>
                <legend>Contact informations</legend>
            </fieldset>
            <fieldset>
                <legend>address informations</legend>
            </fieldset>
        </from>

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="public/scripts/order.js"></script>
<!-- Fin -->

</body>
</html>

