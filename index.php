<?php

include_once('config.php');
include_once(ROOT . 'libs/security.php');

if (Security::isAuthenticated()) {
    header('location: categories.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Home</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/default.css"/>
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
                <form method="post" onsubmit="return false;">
                    <p>
                        <label class="field" for="username">Username</label>
                        <input id="username" name="username" type="text"/>
                    </p>

                    <p>
                        <label class="field" for="password">Password</label>
                        <input id="password" name="password" type="password"/>
                    </p>

                    <p>
                        <input type="submit" value="Login"/>
                    </p>
                </form>
            </div>
        </div>
        <!-- Fin -->

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="js/login.js"></script>
<!-- Fin -->

</body>
</html>
