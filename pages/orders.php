<?php
$title = 'Orders';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/orders.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<fieldset id="storeInfos">
    <legend>Store informations</legend>

    <p>
        <label class="properties">Name : </label>
        <label id="storeName" class="values"></label>
    </p>

    <p>
        <label class="properties">Phone : </label>
        <label id="storePhone" class="values"></label>
    </p>

    <p>
        <label class="properties">Email : </label>
        <label id="storeEmail" class="values"></label>
    </p>

    <p>
        <label class="properties">Address : </label>
        <label id="storeAddress" class="values"></label>
    </p>
</fieldset>

<h1>Orders</h1>

<div id="orders">
</div>

<script src="js/orders.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

