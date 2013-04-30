<?php

include_once('config.php');
include_once(ROOT . 'libs/account.php');
include_once(ROOT . 'libs/cart.php');

if (!Account::isAuthenticated()) {
    header('location: index.php');
}

if (Cart::isEmpty()) {
    header('location: catalog.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Order</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/default.css"/>
    <link type="text/css" rel="stylesheet" href="css/order.css"/>
    <!-- Fin -->

</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <h1>Customer informations</h1>

        <from method="post" onsubmit="return confirmOrder();">
            <fieldset>
                <legend>Contact informations</legend>
                <p>
                    <label for="firstName">First name</label>
                    <input id="firstName" name="firstName" type="text"/>
                </p>

                <p>
                    <label for="lastName">Last name</label>
                    <input id="lastName" name="lastName" type="text"/>
                </p>

                <p>
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email"/>
                </p>

                <p>
                    <label for="emailConfirmation">Email confirmation</label>
                    <input id="emailConfirmation" name="emailConfirmation" type="email"/>
                </p>

                <p>
                    <label for="phoneNumber">Phone number</label>
                    <input id="phoneNumber" name="phoneNumber" type="tel"/>
                </p>
            </fieldset>
            <fieldset>
                <legend>address informations</legend>
                <p>
                    <label for="address">Address</label>
                    <textarea id="address" rows="6" streetNumber"></textarea>
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
                    <label for="zipCode">Zip Code</label>
                    <input id="zipCode" name="zipCode" type="text"/>
                </p>

                <p>
                    <label for="countries">Country</label>
                    <select id="countries" name="countries"></select>
                </p>
            </fieldset>

            <div id="buttons">
                <input id="confirm" name="confirm" type="submit" value="Confirm"/>
                <a id="clear">clear</a>
            </div>
        </from>

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="js/order.js"></script>
<!-- Fin -->

</body>
</html>

