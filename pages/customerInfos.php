<?php
$title = 'Customers Informations';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/customerInfos.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<h1>Customer informations</h1>

<form id="frmOrder" method="post" onsubmit="return false;">
    <ul id="summary"></ul>
    <fieldset>
        <legend>Contact informations</legend>
        <p>
            <label for="firstname">First name</label>
            <input id="firstname" name="firstname" type="text"/>
        </p>

        <p>
            <label for="lastname">Last name</label>
            <input id="lastname" name="lastname" type="text"/>
        </p>

        <p>
            <label for="email1">Email</label>
            <input id="email1" name="email1" type="email"/>
        </p>

        <p>
            <label for="email2">Email confirmation</label>
            <input id="email2" name="email2" type="email"/>
        </p>

        <p>
            <label for="phone">Phone number</label>
            <input id="phone" name="phone" type="tel"/>
        </p>
    </fieldset>

    <fieldset id="addressInfos">
        <legend>Address informations <br/>
            <input id="checkUseStoreAddress" name="checkUseStoreAddress" type="checkbox"/>
            <label id="lblUseStoreAddress">Fill with store address.</label>
        </legend>
        <p>
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="5"></textarea>
        </p>

        <p>
            <label for="city">City</label>
            <input id="city" name="city" type="text"/>
        </p>

        <p>
            <label for="states">State</label>
            <select id="states" name="states"></select>
        </p>

        <p>
            <label for="zip">Zip Code</label>
            <input id="zip" name="zip" type="text"/>
        </p>

        <p>
            <label for="countries">Country</label>
            <select id="countries" name="countries"></select>
        </p>
    </fieldset>

    <div id="buttons">
        <input id="proceed" name="proceed" type="submit" value="proceed"/>
        <a id="clear" class="button">Clear</a>
        <a id="btnCancel" class="button">Cancel</a>
    </div>
</form>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="js/customerInfos.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

