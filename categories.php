<?php

include_once(dirname(__FILE__) . '/authentication.php');

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
    <?php include_once(dirname(__FILE__) . '/_header.php'); ?>
    <div id="content">

        <!-- Liste des catégories. -->
        <div id="categories">
        </div>
        <!-- Fin -->

    </div>
    <?php include_once(dirname(__FILE__) . '/_footer.php'); ?>
</div>

<!-- Scripts. -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script src="public/scripts/categories.js"></script>
<!-- Fin -->

</body>
</html>

