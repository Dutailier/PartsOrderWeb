<?php

include_once('config.php');
include_once(ROOT . 'libs/item.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/types.php');
include_once(ROOT . 'libs/repositories/lines.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/stores.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/receivers.php');
include_once(ROOT . 'libs/interfaces/itransaction.php');
include_once(ROOT . 'libs/repositories/destinations.php');

// Définition des différents status de la transaction.
define('READY', 0);
define('DESTINATION_ISSET', 1);
define('SHIPPING_INFOS_ISSET', 2);
define('IS_OPEN', 3);
define('TYPE_ISSET', 4);
define('IS_PROCEED', 5);

/**
 * Class SessionTransaction
 * Représente une transaction au sein du site.
 * Cette classe encapsule les règles d'affaires.
 */
class SessionTransaction implements ITransaction
{
    const CART_IDENTIFIER = '_CART_';
    const TYPE_IDENTIFIER = '_TYPE_';
    const STORE_IDENTIFIER = '_STORE_';
    const ORDER_IDENTIFIER = '_ORDER_';
    const LINES_IDENTIFIER = '_LINES_';
    const STATUS_IDENTIFIER = '_STATUS_';
    const RECEIVER_IDENTIFIER = '_RECEIVER_';
    const DESTINIATION_IDENTIFIER = '_DESTINATION_';
    const SHIPPING_ADDRESS_IDENTIFIER = '_SHIPPING_ADDRESS_';

    /**
     * Constructeur par défaut.
     */
    public function __construct()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION[self::STATUS_IDENTIFIER])) {
            $_SESSION[self::STATUS_IDENTIFIER] = READY;
        }

        if (!isset($_SESSION[self::CART_IDENTIFIER])) {
            $_SESSION[self::CART_IDENTIFIER] = new SessionCart();
        }

        if (!isset($_SESSION[self::LINES_IDENTIFIER])) {
            $_SESSION[self::LINES_IDENTIFIER] = array();
        }
    }


    /**
     * Permet de copier une autre transaction.
     * @param ITransaction $transaction
     */
    public function Copy(ITransaction $transaction)
    {
        $_SESSION[self::TYPE_IDENTIFIER] = $transaction->getType();
        $_SESSION[self::STORE_IDENTIFIER] = $transaction->getStore();
        $_SESSION[self::STATUS_IDENTIFIER] = $transaction->getStatus();
        $_SESSION[self::RECEIVER_IDENTIFIER] = $transaction->getReceiver();
        $_SESSION[self::DESTINIATION_IDENTIFIER] = $transaction->getDestination();
        $_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER] = $transaction->getShippingAddress();
    }


    /**
     * Retourne un tableau contenant les éléments de la transaction.
     * @return array
     */
    public function getArray()
    {
        $array = array();

        switch ($this->getStatus()) {
            case IS_PROCEED:
                $array['order'] = $this->getOrder()->getArray();

                foreach ($this->getLines() as $line) {
                    $array['lines'][] = $line->getArray();
                }

            case TYPE_ISSET:
            case IS_OPEN:
            case SHIPPING_INFOS_ISSET:
                $array['store'] = $this->getStore()->getArray();
                $array['receiver'] = $this->getReceiver()->getArray();
                $array['shippingAddress'] = $this->getShippingAddress()->getArray();
        }

        return $array;
    }

    /**
     * Définit la destination choisie par le client. (ex: Pour le magasin).
     * @param Destination $destination
     * @throws Exception
     */
    public function setDestination(Destination $destination)
    {
        if ($this->getStatus() != READY) {
            throw new Exception('The destination is already setted.');
        }

        if (!$destination->isAttached()) {
            throw new Exception('The destination must be attached to a database.');
        }

        $_SESSION[self::DESTINIATION_IDENTIFIER] = $destination;

        $this->setStatus(DESTINATION_ISSET);
    }

    /**
     * Définit les informations d'expédition telles que l'adresse, le magasin et le destinataire.
     * @param Address $shippingAddress
     * @param Store $store
     * @param Receiver $receiver
     * @throws Exception
     */
    public function setShippingInfos(Address $shippingAddress, Store $store, Receiver $receiver)
    {
        if ($this->getStatus() != DESTINATION_ISSET && $this->getStatus() != SHIPPING_INFOS_ISSET) {
            throw new Exception('You cannot modify the shipping informations when the transaction is open.');
        }

        $_SESSION[self::STORE_IDENTIFIER] = $store;
        $_SESSION[self::RECEIVER_IDENTIFIER] = $receiver;
        $_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER] = $shippingAddress;

        $this->setStatus(SHIPPING_INFOS_ISSET);
    }

    /**
     * Initie la transaction courrante.
     * (Après cette étape, il sera impossible de modifier les informations d'expédition.)
     * @throws Exception
     */
    public function Open()
    {
        if ($this->getStatus() != SHIPPING_INFOS_ISSET) {
            throw new Exception('The shipping informations must be previously setted.');
        }

        $this->setStatus(IS_OPEN);
    }

    /**
     * Définit le type de produit choisi par le client. (ex: coussin).
     * @param Type $type
     * @throws Exception
     */
    public function setType(Type $type)
    {
        if ($this->getStatus() != IS_OPEN) {
            throw new Exception('The transaction must be previously open.');
        }

        if (!$type->isAttached()) {
            throw new Exception('The filter must be attached to a database.');
        }

        $_SESSION[self::TYPE_IDENTIFIER] = $type;

        $this->setStatus(TYPE_ISSET);
    }


    /**
     * Finalise la transaction.
     * (Après cette étape, la transaction sera supprimée.)
     * @throws Exception
     */
    public function Proceed()
    {
        if ($this->getStatus() != TYPE_ISSET) {
            throw new Exception('The type must be previously setted.');
        }

        if ($this->getCart()->isEmpty()) {
            throw new Exception('The cart cannot be empty.');
        }

        $receiver = Receivers::Attach($this->getReceiver());
        $address = Addresses::Attach($this->getShippingAddress());

        $order = new Order(
            $address->getId(),
            $this->getStore()->getId(),
            $receiver->getId()
        );

        $_SESSION[self::ORDER_IDENTIFIER] = Orders::Attach($order);

        $_SESSION[self::LINES_IDENTIFIER] = array();
        foreach ($this->getCart()->getItems() as $item) {
            $line = new Line(
                $_SESSION[self::ORDER_IDENTIFIER]->getId(),
                $item->getProduct()->getId(),
                $item->getQuantity(),
                $item->getSerial()
            );

            $_SESSION[self::LINES_IDENTIFIER][] = Lines::Attach($line);
        }

        $this->setStatus(IS_PROCEED);
    }

    /**
     * Détruit la transaction courrante.
     */
    public function Destroy()
    {
        unset($_SESSION[self::STORE_IDENTIFIER]);
        unset($_SESSION[self::ORDER_IDENTIFIER]);
        unset($_SESSION[self::LINES_IDENTIFIER]);
        unset($_SESSION[self::STATUS_IDENTIFIER]);
        unset($_SESSION[self::RECEIVER_IDENTIFIER]);
        unset($_SESSION[self::DESTINIATION_IDENTIFIER]);
        unset($_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER]);

        $_SESSION[self::CART_IDENTIFIER]->Clear();
    }


    /**
     * Retourne la transaction choisie par le client.
     * @return mixed
     * @throws Exception
     */
    public function getDestination()
    {
        if ($this->getStatus() < DESTINATION_ISSET) {
            throw new Exception('The filter must be previously setted.');
        }

        return $_SESSION[self::DESTINIATION_IDENTIFIER];
    }


    /**
     * Retourne le type de produit choisi par le client.
     * @return mixed
     * @throws Exception
     */
    public function getType()
    {
        if ($this->getStatus() < TYPE_ISSET) {
            throw new Exception('The type must be previously setted.');
        }

        return $_SESSION[self::TYPE_IDENTIFIER];
    }


    /**
     * Retourne l'addresse d'expédition.
     * @return mixed
     * @throws Exception
     */
    public function getShippingAddress()
    {
        if ($this->getStatus() < SHIPPING_INFOS_ISSET) {
            throw new Exception('The shipping address must be previously setted.');
        }

        return $_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER];
    }


    /**
     * Retourne le magasin à l'origine de la transaction.
     * @return mixed
     * @throws Exception
     */
    public function getStore()
    {
        if ($this->getStatus() < SHIPPING_INFOS_ISSET) {
            throw new Exception('The store must be previously setted.');
        }

        return $_SESSION[self::STORE_IDENTIFIER];
    }


    /**
     * Retourne le destinataire de la transaction.
     * @return mixed
     * @throws Exception
     */
    public function getReceiver()
    {
        if ($this->getStatus() < SHIPPING_INFOS_ISSET) {
            throw new Exception('The store must be previously setted.');
        }

        return $_SESSION[self::RECEIVER_IDENTIFIER];
    }


    /**
     * Retourne le panier d'achats courrant.
     * @return mixed
     * @throws Exception
     */
    private function getCart()
    {
        if ($this->getStatus() < IS_OPEN) {
            throw new Exception('The transaction must be previously opened.');
        }

        return $_SESSION[self::CART_IDENTIFIER];
    }

    /**
     * Ajoute un item à la transaction.
     * @param IItem $item
     * @return mixed
     */
    public function AddItem(IItem $item)
    {
        return $this->getCart()->Add($item);
    }


    /**
     * Retire un item de la transaction.
     * @param IItem $item
     * @return mixed
     */
    public function RemoveItem(IItem $item)
    {
        return $this->getCart()->Remove($item);
    }


    /**
     * Retourne la commande de la transaction.
     * @return mixed
     * @throws Exception
     */
    public function getOrder()
    {
        if ($this->getStatus() < IS_PROCEED) {
            throw new Exception('The transaction must be proceed..');
        }

        return $_SESSION[self::ORDER_IDENTIFIER];
    }


    /**
     * Retourne les lignes de la commande de la transaction.
     * @return mixed
     * @throws Exception
     */
    public function getLines()
    {
        if ($this->getStatus() < IS_PROCEED) {
            throw new Exception('The transaction must be proceed..');
        }

        return $_SESSION[self::LINES_IDENTIFIER];
    }


    /**
     * Retourne le status de la transaction.
     * @return mixed
     */
    public function getStatus()
    {
        return $_SESSION[self::STATUS_IDENTIFIER];
    }


    /**
     * Définit le status de la transaction.
     * @param $status
     */
    private function setStatus($status)
    {
        $_SESSION[self::STATUS_IDENTIFIER] = $status;
    }
}