<?php
$title = 'Order Informations';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/orderInfos.css"/>
<link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<ul id="tabs">
    <li id="btnTabOrder">Order</li>
    <li id="btnTabLogs">Logs</li>
</ul>

<div class="tab" id="tabOrder">
    <!-- Début du contenu. -->
    <fieldset id='orderInfos'>
        <legend>Order Informations</legend>

        <p>
            <label class="properties">Number : </label>
            <label id="number" class="values"></label>
        </p>

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

    <hr/>

    <div id="lines">
    </div>

    <hr/>

    <div id="comments">
    </div>

    <!-- Sommaire des pièces commandées -->
    <form id="summary">
    </form>
    <!-- Fin -->
</div>

<div class="tab" id="tabLogs">
    <div id="logs"></div>
</div>

<div id="cancelDialog" title="Cancellation required" class="dialog">
    Are you sure you want to cancel this order?
</div>

<div id="confirmDialog" title="Confirmation required" class="dialog">
    Are you sure you want to confirm this order?
</div>

<div id="addCommentDialog" title="Add Comment" class="dialog">
    <form id="newComment">
        <textarea id="comment" name="comment"></textarea>
    </form>
</div>

<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="js/orderInfos.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
