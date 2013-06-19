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
        case 'index' :
            if (Security::isInRoleName(ROLE_ADMINISTRATOR)) {
                $page = 'manager';
                break;
            }

        // Pages transactionnelles
        case 'types' :
        case 'products' :
        case 'destinations' :
        case 'shippingInfos' :
            $transaction = new SessionTransaction();

            switch ($transaction->getStatus()) {
                case READY:
                    $page = 'destinations';
                    break;
                case DESTINATION_ISSET:
                    $page = 'receiverInfos';
                    break;
                case SHIPPING_INFOS_ISSET:
                    $page = 'shippingInfos';
                    break;
                case IS_OPEN:
                    $page = 'types';
                    break;
                case TYPE_ISSET:
                    $page = 'products';
                    break;
                case IS_PROCEED:
                    $page = 'orderInfos';
                    break;
            }
            break;

        // Pages administratives
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
if (empty($title) || empty($head) || empty($content)) {
    include_once(ROOT . 'pages/' . 'error' . '.php');
}
?>

<html>
<head>
    <title>Parts Order Web - <?php echo $title; ?></title>

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