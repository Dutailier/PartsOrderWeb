<?php
$title = 'Confirmation';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<script>
    $(document).ready(function () {
        setTimeout(function () {
            window.location = 'index.php';
        }, 3000)
    });
</script>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<h1>Thanks for your order.</h1>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

