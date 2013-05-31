<?php

include_once('config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');

if (!Security::isAuthenticated()) {
    $page = 'login';

} else {
    $page = $_GET['page'];

    // Si aucune page n'est demandée, on redirige l'utilisateur
    // à la liste de produits.
    if (empty($page) || $page == 'index') {
        $page = 'products';
    }

    // Si la page désirée est la liste des produits, mais
    // que la transaction n'a pas débutée, l'utilisateur
    // sera redirigé vers la page des destinations.
    if ($page == 'products') {
        $transaction = new SessionTransaction();

        if (!$transaction->isOpen()) {
            $page = 'destinations';
        }
    }
}

// Chemin de la page demandée.
$file = ROOT . 'pages/' . $page . '.php';

if (!file_exists($file)) {
    $file = ROOT . 'pages/' . 'error.php';

} else {
    include_once($file);

    if (empty($head) || empty($content)) {
        include_once(ROOT . 'pages/' . 'error.php');
    }
}
?>

<html>
<head>
    <title>BabiesRus Parts Order - <?php echo $title; ?></title>

    <link type="text/css" rel="stylesheet" href="css/default.css"/>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <!-- Début de l'en-tête dynamique. -->
    <?php echo $head; ?>
    <!-- Fin de l'en-tête dynamique. -->

</head>
<body>
<div id="wrapper">

    <!-- Début de l'en-tête de la page. -->
    <div id="header-band">
        <div id="header-wrapper">
            <img id="logo-dutailier" src="img/dutailier.png">
            <ul id="menu">

                <?php if ($page != 'login') : ?>
                    <li><a id="btnProducts">Products</a></li>
                    <li><a id="btnLogout">Logout</a></li>
                <?php endif; ?>
                <li><a id="btnContactUs">Contact us</a></li>
            </ul>
            <img id="logo-babiesRus" src="img/babiesrus.png">
        </div>
    </div>
    <!-- Fin de l'en-tête de la page. -->

    <!-- Début du contenu dynamique. -->
    <div id="content">
        <?php echo $content; ?>
    </div>
    <!-- Fin du contenu dynamique. -->

    <!-- Début du pied de page. -->
    <div id="footer-band">
        <div id="footer-wrapper">
            <span id="copyright">Dutailier 2013 &copy;</span>
        </div>
    </div>

    <script src="js/menu.js"></script>
    <!-- Fin du pied de page. -->
</div>
</body>
</html>