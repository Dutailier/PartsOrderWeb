<?php

include_once('config.php');
include_once(ROOT . 'libs/account.php');

if (Account::isAuthenticated()) {
    header('location: categories.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Home</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/layout.css"/>
    <link type="text/css" rel="stylesheet" href="css/login.css"/>
    <!-- Fin -->

</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <!-- Formulaire de connexion. -->
        <div id="background">
            <div id="login">
                <form id="frmLogin" method="post" onsubmit="return login();">
                    <p>
                        <label for="txtUsername">Username</label>
                        <input id="txtUsername" name="txtUsername" type="text"/>
                    </p>

                    <p>
                        <label for="txtPassword">Password</label>
                        <input id="txtPassword" name="txtPassword" type="password"/>
                    </p>

                    <p>
                        <input id="btnLogin" name="btnLogin" type="submit" value="Login"/>
                    </p>
                </form>
            </div>
        </div>
        <!-- Fin -->

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="js/login.js"></script>
<!-- Fin -->

</body>
</html>
