<?php

include_once('config.php');
include_once(ROOT . 'libs/security.php');
include_once(ROOT . 'libs/sessionTransaction.php');

if (!Security::isAuthenticated()) {
    $page = 'login';

} else {
    $page = empty($_GET['page']) ? 'index' : $_GET['page'];

    switch ($page) {
        // Page d'accueil
        case 'login' :
        case 'index' :
            if (Security::isInRoleName(ROLE_ADMINISTRATOR)) {
                $page = 'manager';
                break;
            }

        // Pages transactionnelles
        case 'products' :
        case 'categories' :
        case 'destinations' :
        case 'shippingInfos' :
            $transaction = new SessionTransaction();

            // Redirige l'utilisateur vers une page selon
            // le statut de la transaction courrante.
            switch ($transaction->getStatus()) {
                case TRANSACTION_IS_READY:
                    $page = 'destinations';
                    break;
                case TRANSACTION_DESTINATION_IS_SELECTED:
                    $page = 'receiverInfos';
                    break;
                case TRANSACTION_SHIPPING_INFOS_ARE_SETTED:
                    $page = 'shippingInfos';
                    break;
                case TRANSACTION_IS_OPEN:
                    $page = 'categories';
                    break;
                case TRANSACTION_CATEGORY_IS_SELECTED:
                    $page = 'products';
                    break;
            }
            break;

        // Pages administratives
        case 'export' :
        case 'manager' :
        case 'storeInfos' :
            if (!Security::isInRoleName(ROLE_ADMINISTRATOR)) {
                $page = 'error';
            }
            break;
    }
}

$file = ROOT . 'pages/' . $page . '.php';

// Avant d'inclure la page, on doit vérifier quelle existe.
if (!file_exists($file)) {
    $file = ROOT . 'pages/' . 'error' . '.php';
}

include_once($file);

// On doit vérifier que la page est correctement construite.
if (!isset($title) || !isset($head) || !isset($content)) {
    include_once(ROOT . 'pages/' . 'error' . '.php');
}
?>

<html>
<head>
    <title>Parts Order Web - <?php echo $title; ?></title>

    <link type="text/css" rel="stylesheet" href="css/default.css"/>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="js/noty/layouts/topRight.js"></script>
    <script type="text/javascript" src="js/noty/themes/default.js"></script>

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

                <?php if (Security::isAuthenticated()) { ?>
                    <?php if (Security::isInRoleName(ROLE_STORE)) { ?>
                        <li><a id="btnProducts">Products</a></li>
                        <li><a id="btnOrders">Orders</a></li>
                    <?php } ?>

                    <?php if (Security::isInRoleName(ROLE_ADMINISTRATOR)) { ?>
                        <li><a id="btnManager">Manager</a></li>
                    <?php } ?>

                    <li><a id="btnLogout">Logout</a></li>
                <?php } ?>
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