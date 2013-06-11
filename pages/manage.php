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
</ul>

<div class="tab" id="tabOrders">
    <div id="orders">
        <div id="orderFilters" class="filters">
            <b>Search : </b><input id="number" name="number" type="text" maxlength="11">
            <input id="btnSearchNumber" name="btnSearchNumber" type="button" value="Search"/>

            <div id="dates">
                <b>from : </b><input id="from" class="date" type="text" class="datepicker"/>
                <b>to : </b><input id="to" class="date" type="text" class="datepicker"/>
                <input id="btnRangeOfDates" name="btnRangeOfDates" type="button" value="Go"/>
            </div>
        </div>
    </div>
</div>

<div class="tab" id="tabStores">
    <div id="storeFilters" class="filters">
        <b>Search : </b><input id="username" name="username" type="text">
        <input id="btnSearchUsername" name="btnSearchUsername" type="button" value="Search"/>

        <div id="banners">
            <b>Banner : </b><select id="banner">
            </select>
        </div>
    </div>
    <div id="stores">
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

