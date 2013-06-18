<?php
$title = 'Products';
?>

<?php ob_start(); ?>

    <!-- Début de l'en-tête. -->
    <link type="text/css" rel="stylesheet" href="css/products.css"/>
    <link type="text/css" rel="stylesheet" href="css/shopping.css"/>
    <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>
    <!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

    <div id="transaction">
        <h1>Transaction</h1>
        <fieldset id="shippingInfos">
            <legend>Shipping informations</legend>
            <p>
                <label class="property">Address : </label>
                <label id="shippingAddress" class="value"></label>
            </p>
        </fieldset>
        <fieldset id="storeInfos">
            <legend>Store informations</legend>
            <div class="infos">
                <p>
                    <label class="property">Name : </label>
                    <label id="storeName" class="value"></label>
                </p>

                <p>
                    <label class="property">Phone : </label>
                    <label id="storePhone" class="value"></label>
                </p>

                <p>
                    <label class="property">Email : </label>
                    <label id="storeEmail" class="value"></label>
                </p>

                <p>
                    <label class="property">Address : </label>
                    <label id="storeAddress" class="value"></label>
                </p>
            </div>
            <a class="btnMoreDetails">More details</a>
            <a class="btnLessDetails">Close details</a>
        </fieldset>
        <fieldset id="receiverInfos">
            <legend>Receiver informations</legend>
            <div class="infos">
                <p>
                    <label class="property">Name : </label>
                    <label id="receiverName" class="value"></label>
                </p>

                <p>
                    <label class="property">Phone : </label>
                    <label id="receiverPhone" class="value"></label>
                </p>

                <p>
                    <label class="property">Email : </label>
                    <label id="receiverEmail" class="value"></label>
                </p>
            </div>
            <a class="btnMoreDetails">More details</a>
            <a class="btnLessDetails">Close details</a>
        </fieldset>
        <h2 id="lblProducts">Products</h2>

        <div id="items">
        </div>

        <div id="buttons">
            <input id="btnProceed" name="btnProceed" type="button" value="Proceed"/>
            <a id="btnClear" class="button">Clear</a>
            <a id="btnCancel" class="button">Cancel</a>
        </div>
    </div>

    <!-- Début du contenu. -->
    <!-- Entrée du numéro de série d'une chaise. -->
    <form id="frmSearch" onsubmit="return false;">
        <p>
            <label for="serial">Serial</label>
            <input id="serial" name="serial" type="text" maxlength="11"/>
            <input id="search" name="search" type="submit" value="Search"/>
        </p>

        <div id="filters">
        </div>
    </form>
    <!-- Fin -->

    <div id="help">
        <label>Please enter the 11 digits of the serial number on the manufacturing label of the chair.</label>
        <img src="img/serial.png"/>
    </div>

    <div id="load">
        <label>Please wait...</label>
        <img id="loader" src="img/loader.gif"/>
    </div>

    <!-- Liste des pièces. -->
    <div id="products">
    </div>
    <!-- Fin -->

    <div id="proceedDialog">
        You can't change your order later. Do you want to continue?
    </div>

    <div id="cancelDialog">
        Are your sure you want to cancel your order?
    </div>

    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <script src="js/products.js"></script>
    <!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>