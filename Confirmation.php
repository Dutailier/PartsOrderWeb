<?php

include_once('config.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Confirmation</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/default.css"/>
    <link type="text/css" rel="stylesheet" href="css/confirmation.css"/>
    <!-- Fin -->

</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">
        <fieldset>
            <legend>Retailer informations</legend>
            <label id="retailerId " class="hidden"></label>

            <p>
                <label class="properties">Name : </label>
                <label id="retailerName" class="values">Patates frites paul</label>
            </p>

            <p>
                <label class="properties">Phone : </label>
                <label id="retailerPhone" class="values">1-852-654-7899</label>
            </p>

            <p>
                <label class="properties">Email : </label>
                <label id="retailerPhone" class="values">hugo.lapointe@testtest.com</label>
            </p>

            <p>
                <label class="properties">Address : </label>
                <label id="retailerAddress" class="values">28, rue de la Calèche, St-Basile-le-Grand, 34576, Québec,
                    Canada</label>
            </p>
        </fieldset>
        <fieldset>
            <legend>Customer informations</legend>
            <label id="retailerId " class="hidden"></label>

            <p>
                <label class="properties">Name : </label>
                <label id="retailerName" class="values">Breault & Martineau</label>
            </p>

            <p>
                <label class="properties">Phone : </label>
                <label id="retailerPhone" class="values">1-450-654-8574</label>
            </p>

            <p>
                <label class="properties">Email : </label>
                <label id="retailerPhone" class="values">breauetmartineau@cegepsth.qc.ca</label>
            </p>

            <p>
                <label class="properties">Address : </label>
                <label id="retailerAddress" class="values">299 Bd. Sir-Wilfrid-Laurier, St-Jean-Baptiste-Sur-Richelieu,
                    12345 Ontario, États-Unis</label>
            </p>
        </fieldset>
        <fieldset>
            <legend>Shipping informations</legend>
            <p>
                <label class="properties">Address : </label>
                <label id="retailerAddress" class="values">23495 Boulevard Laframboise, St-Hyacinthe, 12345, Québec,
                    Canada</label>
            </p>
        </fieldset>

        <hr id="line"/>

        <div id="items">
            <div class="item" data-typeid="6" data-categoryid="3">
                <div class="details"><label class="quantity">5</label><label class="name">Lock Handle</label><label
                        class="serialGlider">22222222222</label></div>
            </div>
            <div class="item" data-typeid="6" data-categoryid="3">
                <div class="details"><label class="quantity">5</label><label class="name">Lock Handle</label><label
                        class="serialGlider">22222222222</label></div>
            </div>
            <div class="item" data-typeid="6" data-categoryid="3">
                <div class="details"><label class="quantity">5</label><label class="name">Lock Handle</label><label
                        class="serialGlider">22222222222</label></div>
            </div>
            <div class="item" data-typeid="6" data-categoryid="3">
                <div class="details"><label class="quantity">5</label><label class="name">Lock Handle</label><label
                        class="serialGlider">22222222222</label></div>
            </div>
            <div class="item" data-typeid="6" data-categoryid="3">
                <div class="details"><label class="quantity">5</label><label class="name">Lock Handle</label><label
                        class="serialGlider">22222222222</label></div>
            </div>
        </div>

        <!-- Sommaire des pièces commandées -->
        <form id="summary">
            <input id="btnOrder" name="btnOrder" type="button" value="Confirm"/>
        </form>
        <!-- Fin -->

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/confirmation.js"></script>
<!-- Fin -->

</body>
</html>

