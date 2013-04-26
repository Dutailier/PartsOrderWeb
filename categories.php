<?php

include_once 'authentication.php';

if (!isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Categories</title>

    <!-- Feuilles de style. -->
    <link type="text/css" rel="stylesheet" href="public/css/default.css"/>
    <link type="text/css" rel="stylesheet" href="public/css/categories.css"/>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php include_once '_header.php' ?>
    <div id="content">

        <!-- Liste des catégories. -->
        <div id="categories">
        </div>
        <!-- Fin -->

    </div>
    <?php include_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="public/scripts/categories.js"></script>
<!-- Fin -->

</body>
</html>

