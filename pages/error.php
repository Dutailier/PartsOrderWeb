<?php
$title = 'Error';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<h1>404 - Page not found!</h1>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

