<?php
$title = 'Manager';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/manager.css"/>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<div id="tabs">
    <ul>
        <li id="btnTabOrders">Orders</li>
        <li id="btnTabStores">Stores</li>
        <li id="btnTabLogs">Logs</li>
    </ul>
</div>

<div class="tab" id="tabOrders">
    <div id="ordersFilters" class="filters">
        <div class="keyWordsWrapper">
            <label for="orderKeyWords">Search : </label>
            <input id="orderKeyWords" name="orderKeyWords" class="keyWords" type="text">
        </div>
        <input id="btnExport" name="btnExport" type="button" value="Export"/>
        <div id="datesWrapper">
            <label for="orderFrom">From :</label>
            <input id="orderFrom" name="orderFrom" class="date" type="text" class="datepicker"/>
            <label for="orderTo">To : </label>
            <input id="orderTo" name="orderTo" class="date" type="text" class="datepicker"/>
        </div>
    </div>
    <div id="orders">
        <div id="ordersLoader" class="loader">
            <label>Please wait...</label>
            <img id="loader" src="img/loader.gif"/>
        </div>
        <div id="ordersEmpty" class="empty hidden">
            <label>No orders found...</label>
        </div>
    </div>
</div>

<div class="tab" id="tabStores">
    <div id="storesFilters" class="filters">
        <div class="keyWordsWrapper">
            <label for="storeKeyWords">Search : </label>
            <input id="storeKeyWords" name="storeKeyWords" class="keyWords" type="text">
        </div>
        <input id="btnAddStore" name="btnAddStore" type="button" value="Add Store"/>
        <div id="bannersWrapper">
            <label for="banners">Banner : </label>
            <select id="banners"> </select>
        </div>
    </div>
    <div id="stores">
        <div id="storesLoader" class="loader">
            <label>Please wait...</label>
            <img id="loader" src="img/loader.gif"/>
        </div>
        <div id="storesEmpty" class="empty hidden">
            <label>No stores found...</label>
        </div>
    </div>
</div>

<div class="tab" id="tabLogs">
    <div id="logsFilters" class="filters">
        <div class="keyWordsWrapper">
            <label for="logKeyWords">Search : </label>
            <input id="logKeyWords" name="logKeyWords" class="keyWords" type="text">
        </div>
        <div id="datesWrapper">
            <label for="logFrom">From :</label>
            <input id="logFrom" name="logFrom" class="date" type="text" class="datepicker"/>
            <label for="logTo">To : </label>
            <input id="logTo" name="logTo" class="date" type="text" class="datepicker"/>
        </div>
    </div>
    <div id="logs">
        <div id="logsLoader" class="loader">
            <label>Please wait...</label>
            <img id="loader" src="img/loader.gif"/>
        </div>
        <div id="logsEmpty" class="empty hidden">
            <label>No logs found...</label>
        </div>
    </div>
</div>

<div id="confirmDialog" class="hidden">
    Are your sure you want to confirm the order : <label class="orderNumber"></label>?
</div>

<div id="cancelDialog" class="hidden">
    Are your sure you want to cancel the order : <label class="orderNumber"></label>?
</div>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/manager.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

