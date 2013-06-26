<?php
$title = 'Order Informations';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/shippingInfos.css"/>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<fieldset id="shippingInfos">
    <legend>Shipping Informations</legend>
    <p>
        <label class="properties">Address : </label>
        <label id="shippingAddress" class="values"></label>
    </p>
</fieldset>
<fieldset id="storeInfos">
    <legend>Store Informations</legend>
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
<fieldset id="receiverInfos">
    <legend>Receiver Informations</legend>
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

<form id="summary">
    <input id="btnConfirm" name="btnConfirm" type="button" value="Confirm"/>
    <input id="btnEdit" name="btnEdit" type="button" value="Edit"/>
    <a id="btnCancel" class="button">Cancel</a>
</form>

<div id="cancelDialog" class="hidden">
    Are you sure you want to cancel this order?
</div>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/shippingInfos.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
