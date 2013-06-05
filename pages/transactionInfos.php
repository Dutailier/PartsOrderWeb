<?php
$title = 'Order Informations';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/transactionInfos.css"/>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<fieldset id='orderInfos'>
    <legend>Order Informations</legend>

    <p>
        <label class="properties">Creation date : </label>
        <label id="creationDate" class="values"></label>
    </p>

    <p>
        <label class="properties">Status : </label>
        <label id="status" class="values"></label>
    </p>
</fieldset>
<fieldset>
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
<fieldset>
    <legend>Receiver informations</legend>

    <p>
        <label class="properties">Name : </label>
        <label id="receiverName" class="values"></label>
    </p>

    <p>
        <label class="properties">Phone : </label>
        <label id="receiverPhone" class="values"></label>
    </p>

    <p>
        <label class="properties">Email : </label>
        <label id="receiverEmail" class="values"></label>
    </p>
</fieldset>
<fieldset>
    <legend>Shipping informations</legend>
    <p>
        <label class="properties">Address : </label>
        <label id="shippingAddress" class="values"></label>
    </p>
</fieldset>

<hr id="line"/>

<div id="lines">
</div>

<!-- Sommaire des pièces commandées -->
<form id="summary">
    <input id="btnConfirm" name="btnConfirm" type="button" value="Confirm"/>
    <a id="btnCancel">Cancel</a>
</form>
<!-- Fin -->

<div id="dialog" title="Confirmation required" class="dialog">
    Are you sure you want cancel this order?
</div>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/transactionInfos.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
