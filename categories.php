<?php

require_once 'authentication.php';

if (!isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Categories</title>

    <!-- Feuilles de style. -->
    <link rel="stylesheet" type="text/css" href="public/css/default.css"/>
    <link rel="stylesheet" type="text/css" href="public/css/categories.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php require_once '_header.php' ?>
    <div id="content">

        <!-- Liste des catégories. -->
        <div id="categories">
        </div>
        <!-- Fin -->

    </div>
    <?php require_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="public/scripts/jquery.min.js"></script>
<script src="public/scripts/categories.js"></script>
<!-- Fin -->
</body>
</html>

