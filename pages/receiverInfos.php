<?php
$title = 'Customers Informations';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/receiverInfos.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<h1>Receiver informations</h1>

<form id="frmOrder" method="post" onsubmit="return false;">
    <ul id="summary"></ul>
    <fieldset>
        <legend>Contact informations</legend>
        <p>
            <label for="firstname">Name</label>
            <input id="name" name="name" type="text"/>
        </p>

        <p>
            <label for="phone">Phone number</label>
            <input id="phone" name="phone" type="tel"/>
        </p>

        <p>
            <label for="email1">Email</label>
            <input id="email1" name="email1" type="email"/>
        </p>

        <p>
            <label for="email2">Email confirmation</label>
            <input id="email2" name="email2" type="email"/>
        </p>
    </fieldset>

    <fieldset id="addressInfos">
        <legend>Address informations</legend>
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
        <input id="proceed" name="proceed" type="submit" value="Submit"/>
        <a id="clear" class="button">Clear</a>
        <a id="btnCancel" class="button">Cancel</a>
    </div>
</form>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="js/receiverInfos.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>
