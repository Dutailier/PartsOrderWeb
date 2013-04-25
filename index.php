<?php

require_once 'protected/authentication.php';

if (isAuthenticated()) {
    header('location: categories.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Home</title>

    <!-- Feuilles de style. -->
    <link rel="stylesheet" type="text/css" href="public/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="public/css/login.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php require_once '_header.php' ?>
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
    <?php require_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="public/scripts/jquery.min.js"></script>
<script src="public/scripts/login.js"></script>
<!-- Fin -->
</body>
</html>
