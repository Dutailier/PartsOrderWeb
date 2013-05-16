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
    <link type="text/css" rel="stylesheet" href="css/orderInfos.css"/>
    <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <!-- Fin -->

</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">
        <fieldset>
            <legend>Retailer informations</legend>

            <p>
                <label class="properties">Name : </label>
                <label id="retailerName" class="values"></label>
            </p>

            <p>
                <label class="properties">Phone : </label>
                <label id="retailerPhone" class="values"></label>
            </p>

            <p>
                <label class="properties">Email : </label>
                <label id="retailerEmail" class="values"></label>
            </p>

            <p>
                <label class="properties">Address : </label>
                <label id="retailerAddress" class="values"></label>
            </p>
        </fieldset>
        <fieldset>
            <legend>Customer informations</legend>

            <p>
                <label class="properties">Name : </label>
                <label id="customerName" class="values"></label>
            </p>

            <p>
                <label class="properties">Phone : </label>
                <label id="customerPhone" class="values"></label>
            </p>

            <p>
                <label class="properties">Email : </label>
                <label id="customerEmail" class="values"></label>
            </p>

            <p>
                <label class="properties">Address : </label>
                <label id="customerAddress" class="values"></label>
            </p>
        </fieldset>
        <fieldset>
            <legend>Shipping informations</legend>
            <p>
                <label class="properties">Address : </label>
                <label id="shippingAddress" class="values"></label>
            </p>
        </fieldset>

        <hr id="line"/>

        <div id="lines">
        </div>

        <!-- Sommaire des pièces commandées -->
        <form id="summary">
            <input id="btnConfirm" name="btnConfirm" type="button" value="Confirm"/>
            <a id="btnCancel">cancel</a>
        </form>
        <!-- Fin -->

        <div id="dialog" title="Confirmation required" class="dialog">
            Are you sure you want cancel this order?
        </div>

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/orderInfos.js"></script>
<!-- Fin -->

</body>
</html>

