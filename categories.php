<?php

include_once('config.php');
include_once(ROOT . 'libs/account.php');

if (!Account::isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Categories</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="css/layout.css"/>
    <link type="text/css" rel="stylesheet" href="css/categories.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once(ROOT . '_header.php'); ?>
    <div id="content">

        <!-- Liste des catégories. -->
        <div id="categories">
        </div>
        <!-- Fin -->

    </div>
    <?php include_once(ROOT . '_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="js/categories.js"></script>
<!-- Fin -->

</body>
</html>

