<?php

include_once 'authentication.php';

if (isAuthenticated()) {
    header('location: categories.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Home</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="public/css/default.css"/>
    <link type="text/css" rel="stylesheet" href="public/css/login.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once '_header.php' ?>
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
    <?php include_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="public/scripts/login.js"></script>
<!-- Fin -->

</body>
</html>
