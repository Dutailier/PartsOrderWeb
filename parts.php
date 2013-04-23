<html>
<head>
    <title>BabiesRus Parts Order - Parts</title>

    <!-- Feuilles de style. -->
    <style><?php require_once 'public/css/default.css' ?></style>
    <style><?php require_once 'public/css/parts.css' ?></style>
    <!-- Fin -->
</head>
<body>
<div id="wrapper">
    <?php require_once '_header.php' ?>
    <div id="content">

        <form id="frmSerialGlider" method="get" onsubmit="return validSerialGlider();">
            <p>
                <label>Serial glider</label>
                <input id="txtSerialGlider" name="txtSerialGlider" type="text"/>
                <input id="btnSubmit" name="btnSubmit" type="submit" value="Search"/>
            </p>

            <p>
                <label id="lblWarning" name="lblWarning" class="warning"/>
            </p>
        </form>
        <!-- Liste des pièces. -->
        <div id="parts">
        </div>
        <!-- Fin -->

    </div>
    <?php require_once '_footer.php' ?>
</div>

<!-- Scripts. -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
<script><?php require_once 'public/scripts/parts.js' ?></script>
<!-- Fin -->
</body>
</html>