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
        <li id="btnTabOrders">Order</li>
        <li id="btnTabLogs">Logs</li>
    </ul>

    <?php if (Security::isInRoleName(ROLE_ADMINISTRATOR)) : ?>
        <a id="btnBackManage" class="button">Back to manager</a>
    <?php endif ?>
</div>

<div id="tabOrders" class="tab">
    <div id="orderFilters" class="filters">
        <div id="numberWrapper">
            <label for="number">Search : </label>
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
<script src="js/orders.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

