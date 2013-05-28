<?php
$title = 'Order Informations';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/shippingInfos.css"/>
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
<fieldset id="RetailerInfos">
    <legend>Store Informations</legend>
    <p>
        <label class="properties">Name : </label>
        <label id="retailerName" class="values"></label>
    </p>

    <p>
        <label class="properties">Phone : </label>
        <label id="retailerPhone" class="values"></label>
    </p>

    <p>
        <label class="properties">Email : </label>
        <label id="retailerEmail" class="values"></label>
    </p>

    <p>
        <label class="properties">Address : </label>
        <label id="retailerAddress" class="values"></label>
    </p>
</fieldset>
<fieldset id="CustomerInfos">
    <legend>Receiver Informations</legend>
    <p>
        <label class="properties">Name : </label>
        <label id="customerName" class="values"></label>
    </p>

    <p>
        <label class="properties">Phone : </label>
        <label id="customerPhone" class="values"></label>
    </p>

    <p>
        <label class="properties">Email : </label>
        <label id="customerEmail" class="values"></label>
    </p>

    <p>
        <label class="properties">Address : </label>
        <label id="customerAddress" class="values"></label>
    </p>
</fieldset>

<form id="summary">
    <input id="btnConfirm" name="btnConfirm" type="button" value="Confirm"/>
    <input id="btnEdit" name="btnEdit" type="button" value="Edit"/>
    <a id="btnCancel" class="button">Cancel</a>
</form>

<script src="js/shippingInfos.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
