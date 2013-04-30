<?php

include_once('config.php');
include_once(ROOT . 'libs/account.php');

if (!Account::isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Parts</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/layout.css"/>
    <link type="text/css" rel="stylesheet" href="css/types.css"/>
    <link type="text/css" rel="stylesheet" href="css/buttons.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <!-- Entrée du numéro de série d'une chaise. -->
        <form id="frmSerialGlider" method="get" onsubmit="return validSerialGlider();">
            <p>
                <label for="txtSerialGlider">Serial glider</label>
                <input id="txtSerialGlider" name="txtSerialGlider" type="text"/>
                <input id="btnSubmit" name="btnSubmit" type="submit" value="Search"/>
            </p>

            <p>
                <label id="lblWarning" name="lblWarning" class="warning"/>
            </p>
        </form>
        <!-- Fin -->

        <!-- Liste des pièces. -->
        <div id="partTypes">
        </div>
        <!-- Fin -->

    </div>
    <?php include_once(ROOT . '/_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="js/types.js"></script>
<!-- Fin -->
</body>
</html>