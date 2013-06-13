<?php
$title = 'Manage';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/manage.css"/>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<ul id="tabs">
    <li id="btnTabOrders">Orders</li>
    <li id="btnTabStores">Stores</li>
    <li id="btnTabLogs">Logs</li>
</ul>

<div class="tab" id="tabOrders">
    <div id="orderFilters" class="filters">
        <div id="numberWrapper">
            <label for="number">Search by number : </label>
            <input id="number" name="number" type="text" maxlength="11">
        </div>
        <div id="datesWrapper">
            <label for="from">From :</label>
            <input id="from" class="date" type="text" class="datepicker"/>
            <label for="to">To : </label>
            <input id="to" class="date" type="text" class="datepicker"/>
        </div>
    </div>
    <div id="orders">
    </div>
</div>

<div class="tab" id="tabStores">
    <div id="storeFilters" class="filters">
        <div id="usernameWrapper">
            <label for="username">Search by username : </label>
            <input id="username" name="username" type="text">
        </div>
        <div id="bannersWrapper">
            <label for="banners">Banner : </label>
            <select id="banners"> </select>
        </div>
    </div>
    <div id="stores">
    </div>
</div>

<div class="tab" id="tabLogs">
    <div id="logs"></div>
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

