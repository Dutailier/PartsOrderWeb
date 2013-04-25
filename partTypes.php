<?php

require_once 'authentication.php';

if (!isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Parts</title>

    <!-- Feuilles de style. -->
    <link rel="stylesheet" type="text/css" href="public/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="public/css/partTypes.css"/>
    <link rel="stylesheet" type="text/css" href="public/css/cart.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php require_once '_header.php' ?>
    <div id="content">

        <!-- Entrée du numéro de série d'une chaise. -->
        <form id="frmSerialGlider" method="get" onsubmit="return validSerialGlider();">
            <p>
                <label for="txtSerialGlider">Serial glider</label>
                <input id="txtSerialGlider" name="txtSerialGlider" type="text"/>
                <input id="btnSubmit" name="btnSubmit" type="submit" value="Search"/>
            </p>

            <p>
                <label id="lblWarning" class="warning"></label>
            </p>
        </form>
        <!-- Fin -->

        <!-- Liste des pièces. -->
        <div id="partTypes">
        </div>
        <!-- Fin -->

    </div>
    <?php require_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="public/scripts/jquery.min.js"></script>
<script src="public/scripts/partTypes.js"></script>
<!-- Fin -->
</body>
</html>