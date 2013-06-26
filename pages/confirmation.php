<?php
$title = 'Confirmation';
?>

<?php ob_start(); ?>

<!-- Début de l'en-tête. -->
<script>
    $(document).ready(function () {
        setTimeout(function () {
            window.location = 'index.php';
        }, 5000)
    });
</script>
<!-- Fin de l'en-tête. -->

<?php $head = ob_get_contents(); ?>
<?php ob_clean(); ?>

<!-- Début du contenu. -->
<h1>Thank you for your order.</h1>
<h2>A confirmation email has been sent.</h2>
<p>You will be redirected...</p>
<!-- Fin du contenu. -->

<?php $content = ob_get_contents(); ?>
<?php ob_end_clean(); ?>

