<?php require_once 'protected/authentication.php' ?>

<html>
<head>
    <title>BabiesRus Parts Order - Parts</title>

    <!-- Feuilles de style. -->
    <style><?php require_once 'public/css/default.css' ?></style>
    <style><?php require_once 'public/css/partTypes.css' ?></style>
    <style><?php require_once 'public/css/cart.css' ?></style>
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
                <label id="lblWarning" name="lblWarning" class="warning"/>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script><?php require_once 'public/scripts/partTypes.js' ?></script>
<!-- Fin -->
</body>
</html>