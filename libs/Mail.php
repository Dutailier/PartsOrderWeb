<?php

require_once(ROOT . 'phpmailer/class.phpmailer.php');

/**
 * Class Mail
 * Gère les méthodes relatives à l'envoi de courriels.
 */
class Mail
{
    /**
     * Retourne une instance de PHPmailer.
     * @return PHPMailer
     */
    private static function getPHPmailer()
    {
        $phpmailer = new PHPMailer();


        $phpmailer->IsSMTP();
        $phpmailer->IsHTML(true);
        $phpmailer->Host = SMTP_HOST;
        $phpmailer->From = SMTP_USERNAME;
        $phpmailer->Password = SMTP_PASSWORD;
        $phpmailer->FromName = 'Parts Order Web';

        return $phpmailer;
    }

    /**
     * Envoie une confirmation de la commande.
     * @param $order
     * @throws Exception
     */
    public static function SendOrderConfirmation($order)
    {
        $phpmailer = self::getPHPmailer();

        $store = $order->getStore();
        $receiver = $order->getReceiver();

        $phpmailer->AddAddress(AGENT_EMAIL, AGENT_NAME);
        $phpmailer->AddAddress($store->getEmail(), $store->getName());
        $phpmailer->AddAddress($receiver->getEmail(), $receiver->getName());

        $phpmailer->Subject = 'Parts Order Web - Confirmation #' . $order->getNumber();

        $document = '';
        include_once(ROOT . 'documents/orderConfirmation.php');
        $phpmailer->Body = $document;

        if (!$phpmailer->Send()) {
            throw new Exception($phpmailer->ErrorInfo);
        }

        $phpmailer->SmtpClose();
        unset($phpmailer);
    }
}