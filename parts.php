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
    <link type="text/css" rel="stylesheet" href="css/parts.css"/>
    <link type="text/css" rel="stylesheet" href="css/buttons.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <!-- Entrée du numéro de série d'une chaise. -->
        <form id="frmSearch" onsubmit="return false;">
            <p>
                <label for="serial">Serial</label>
                <input id="serial" name="serial" type="text"/>
                <input id="search" name="search" type="submit" value="Search"/>
            </p>
        </form>
        <!-- Fin -->

        <div id="help">
            <label>Please enter the 11 digits of the serial number on the manufacturing label of the chair.</label>
            <img src="img/serial.png"/>
        </div>

        <!-- Liste des pièces. -->
        <div id="parts">
        </div>
        <!-- Fin -->

        <div id="buttons" class="hidden">
            <input id="btnCart" type="button" value="View cart"/>
            <a id="backCategories" class="button">back</a>
        </div>

    </div>
    <?php include_once(ROOT . '/_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="js/parts.js"></script>
<!-- Fin -->
</body>
</html>