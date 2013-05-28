<?php
$title = 'Home';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<link type="text/css" rel="stylesheet" href="css/login.css"/>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<div id="background">
    <div id="login">
        <form method="post" onsubmit="return false;">
            <p>
                <label class="field" for="username">Username</label>
                <input id="username" name="username" type="text"/>
            </p>

            <p>
                <label class="field" for="password">Password</label>
                <input id="password" name="password" type="password"/>
            </p>

            <p>
                <input type="submit" value="Login"/>
            </p>
        </form>
    </div>
</div>
<script src="http://jzaefferer.github.com/jquery-validation/jquery.validate.js"></script>
<script src="js/login.js"></script>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

