<?php

require_once 'protected/authentication.php';

if(!isAuthenticated()) {
    header('location: index.php');
}

?>

<html>
<head>
    <title>BabiesRus Parts Order - Categories</title>

    <!-- Feuilles de style. -->
    <style><?php require_once 'public/css/default.css' ?></style>
    <style><?php require_once 'public/css/categories.css' ?></style>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script><?php require_once 'public/scripts/categories.js' ?></script>
<!-- Fin -->
</body>
</html>

