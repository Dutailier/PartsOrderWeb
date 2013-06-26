<?php

require_once(ROOT . 'libs/document.php');
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
    private static function getMail()
    {
        $mail = new PHPMailer();

        $mail->IsSMTP();
        $mail->Host = SMTP_HOST;
        $mail->Password = SMTP_PASSWORD;

        $mail->From = SMTP_FROM;
        $mail->FromName = 'Parts Order Web';

        return $mail;
    }

    /**
     * Envoie une confirmation de commande par courriel.
     * @param $order
     */
    public static function SendOrderConfirmation($order)
    {
        $mail = self::getMail();

        $receiver = $order->getReceiver();
        $store = $order->getStore();

        $mail->Subject = 'Parts Order Web - Confirmation #' . $order->getNumber();

        $path = ROOT . 'documents/orderConfirmation.php';

        $parameters = array(
            'order' => $order->getArray(),
            'receiver' => $receiver->getArray(),
            'store' => $store->getArray(),
            'shippingAddress' => $order->getShippingAddress()->getArray(),
        );

        $parameters['lines'] = array();
        foreach ($order->getLines() as $line) {
            $parameters['lines'][] = $line->getArray();
        }

        $parameters['comments'] = array();
        foreach ($order->getComments() as $comment) {
            $parameters['comments'][] = $comment->getArray();
        }

        $mail->IsHTML(true);
        $mail->Body = Document::getContents($path, $parameters);

        // Pour une raison inconnue, on ne peut pas envoyer un courriel à plus
        // d'une addresse à la fois.

        // Envoie du courriel à l'agent.
        $mail->AddAddress(AGENT_EMAIL, AGENT_NAME);
        $mail->Send();
        $mail->ClearAddresses();

        // Envoie du courriel au magasin.
        $mail->AddAddress($store->getEmail(), $store->getName());
        $mail->Send();
        $mail->ClearAddresses();

        if ($store->getEmail() != $receiver->getEmail()) {
            // Envoie du courriel au client final.
            $mail->AddAddress($receiver->getEmail(), $receiver->getName());
            $mail->Send();
        }

        $mail->SmtpClose();
    }
}