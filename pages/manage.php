<?php
$title = 'Manage';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/manage.css"/>
<link type="text/css" rel="stylesheet" href="css/order.css"/>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<ul id="tabs">
    <li id="btnLastOrders">Last Orders</li>
    <li id="btnStores">Stores List</li>
</ul>

<div class="tab" id="tabOrders">
    <div id="orders">
        <p>Click on an order for more details.</p>
    </div>
</div>

<div class="tab" id="tabStores">
    <div id="banners">
        <p>Click on a banner to see his stores.</p>
    </div>
    <div id="stores">
        <p>Click on a store for more details.</p>
    </div>
</div>

<div id="confirmDialog">
    Are your sure you want to confirm the order : <label class="orderNumber"></label>?
</div>

<div id="cancelDialog">
    Are your sure you want to cancel the order : <label class="orderNumber"></label>?
</div>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/manage.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

