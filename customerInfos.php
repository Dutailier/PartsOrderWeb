<?php

include_once('config.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/security.php');

if (!Security::isAuthenticated()) {
    header('location: index.php');
}

if ((new SessionCart())->isEmpty()) {
    header('location: catalog.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Order</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/default.css"/>
    <link type="text/css" rel="stylesheet" href="css/customerInfos.css"/>
    <!-- Fin -->

</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <h1>Customer informations</h1>

        <form id="frmOrder" method="post" onsubmit="return false;">
            <ul id="summary"></ul>
            <fieldset>
                <legend>Contact informations</legend>
                <p>
                    <label for="firstname">First name</label>
                    <input id="firstname" name="firstname" type="text"/>
                </p>

                <p>
                    <label for="lastname">Last name</label>
                    <input id="lastname" name="lastname" type="text"/>
                </p>

                <p>
                    <label for="email1">Email</label>
                    <input id="email1" name="email1" type="email"/>
                </p>

                <p>
                    <label for="email2">Email confirmation</label>
                    <input id="email2" name="email2" type="email"/>
                </p>

                <p>
                    <label for="phone">Phone number</label>
                    <input id="phone" name="phone" type="tel"/>
                </p>
            </fieldset>
            <fieldset>
                <legend>Address informations</legend>
                <p>
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="5"></textarea>
                </p>

                <p>
                    <label for="city">City</label>
                    <input id="city" name="city" type="text"/>
                </p>

                <p>
                    <label for="states">State</label>
                    <select id="states" name="states"></select>
                </p>

                <p>
                    <label for="zip">Zip Code</label>
                    <input id="zip" name="zip" type="text"/>
                </p>

                <p>
                    <label for="countries">Country</label>
                    <select id="countries" name="countries"></select>
                </p>
            </fieldset>

            <div id="buttons">
                <input id="proceed" name="proceed" type="submit" value="proceed"/>
                <a id="clear">clear</a>
            </div>
        </form>

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
<script src="js/customerInfos.js"></script>
<!-- Fin -->

</body>
</html>

