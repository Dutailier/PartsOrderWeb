<?php
$title = 'Orders';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/orders.css"/>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
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

<div id="tabs">
    <ul>
        <li id="btnTabOrders">Orders</li>
        <li id="btnTabLogs">Logs</li>
    </ul>

    <?php if (Security::isInRoleName(ROLE_ADMINISTRATOR)) { ?>
        <a id="btnBackManager" class="button">Back to manager</a>
    <?php } ?>
</div>

<div id="tabOrders" class="tab">
    <div id="ordersFilters" class="filters">
        <div class="keyWordsWrapper">
            <label for="orderKeyWords">Search : </label>
            <input id="orderKeyWords" name="orderKeyWords" type="text">
        </div>
        <div id="datesWrapper">
            <label for="orderFrom">From :</label>
            <input id="orderFrom" name="orderFrom" class="date" type="text" class="datepicker"/>
            <label for="orderTo">To : </label>
            <input id="orderTo" name="orderTo" class="date" type="text" class="datepicker"/>
        </div>
    </div>
    <div id="orders">
    </div>
</div>

<div class="tab" id="tabLogs">
    <div id="logsFilters" class="filters">
        <div class="keyWordsWrapper">
            <label for="logKeyWords">Search : </label>
            <input id="logKeyWords" name="logKeyWords" type="text">
        </div>
        <div id="datesWrapper">
            <label for="logFrom">From :</label>
            <input id="logFrom" name="logFrom" class="date" type="text" class="datepicker"/>
            <label for="logTo">To : </label>
            <input id="logTo" name="logTo" class="date" type="text" class="datepicker"/>
        </div>
    </div>
    <div id="logs"></div>
</div>

<div id="confirmDialog">
    Are your sure you want to confirm the order : <label class="orderNumber"></label>?
</div>

<div id="cancelDialog">
    Are your sure you want to cancel the order : <label class="orderNumber"></label>?
</div>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/orders.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

