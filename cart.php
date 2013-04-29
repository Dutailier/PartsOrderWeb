<?php

include_once(dirname(__FILE__) . '/authentication.php');

if (!isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Parts</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="public/css/default.css"/>
    <link type="text/css" rel="stylesheet" href="public/css/cart.css"/>
    <link type="text/css" rel="stylesheet" href="public/css/shoppingButtons.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once(dirname(__FILE__) . '/_header.php'); ?>
    <div id="content">

        <h1>Parts ordered</h1>
        <!-- Liste des items. -->
        <div id="parts">
            <div class="part">
                <div class="details">
                    <label class="quantity">5</label>
                    <label class="name">Asdf dldkwoe ldfa dlfklsla</label>
                    <label class="serial">11236544785</label>
                </div>
                <div class="buttons">
                    <input class="removeCart" type="button"/>
                    <input class="addCart" type="button"/>
                </div>
            </div>
            <div class="part">
                <div class="details">
                    <label class="quantity">5</label>
                    <label class="name">Asdf dldkwoe ldfa dlfklsla</label>
                    <label class="serial">11236544785</label>
                </div>
                <div class="buttons">
                    <input class="removeCart" type="button"/>
                    <input class="addCart" type="button"/>
                </div>
            </div>
            <div class="part">
                <div class="details">
                    <label class="quantity">5</label>
                    <label class="name">Asdf dldkwoe ldfa dlfklsla</label>
                    <label class="serial">11236544785</label>
                </div>
                <div class="buttons">
                    <input class="removeCart" type="button"/>
                    <input class="addCart" type="button"/>
                </div>
            </div>
            <div class="part">
                <div class="details">
                    <label class="quantity">5</label>
                    <label class="name">Asdf dldkwoe ldfa dlfklsla</label>
                    <label class="serial">11236544785</label>
                </div>
                <div class="buttons">
                    <input class="removeCart" type="button"/>
                    <input class="addCart" type="button"/>
                </div>
            </div>
            <div class="part">
                <div class="details">
                    <label class="quantity">5</label>
                    <label class="name">Asdf dldkwoe ldfa dlfklsla</label>
                    <label class="serial">11236544785</label>
                </div>
                <div class="buttons">
                    <input class="removeCart" type="button"/>
                    <input class="addCart" type="button"/>
                </div>
            </div>
            <div class="part">
                <div class="details">
                    <label class="quantity">5</label>
                    <label class="name">Asdf dldkwoe ldfa dlfklsla</label>
                    <label class="serial">11236544785</label>
                </div>
                <div class="buttons">
                    <input class="removeCart" type="button"/>
                    <input class="addCart" type="button"/>
                </div>
            </div>
        </div>
        <!-- Fin -->

        <!-- Sommaire des pièces commandées -->
        <form id="summary">
            <input id="btnOrder" name="btnOrder" type="submit" value="order"/>
            <a id="btnClear">clear</a>
        </form>
        <!-- Fin -->

    </div>
    <?php include_once(dirname(__FILE__) . '/_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="public/scripts/cart.js"></script>
<!-- Fin -->
</body>
</html>