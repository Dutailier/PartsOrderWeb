<?php

include_once('config.php');
include_once(ROOT . 'libs/item.php');
include_once(ROOT . 'libs/sessionCart.php');
include_once(ROOT . 'libs/repositories/categories.php');
include_once(ROOT . 'libs/repositories/lines.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/stores.php');
include_once(ROOT . 'libs/repositories/addresses.php');
include_once(ROOT . 'libs/repositories/receivers.php');
include_once(ROOT . 'libs/repositories/destinations.php');

// Définition des différents status de la transaction.
define('TRANSACTION_IS_READY', 0);
define('TRANSACTION_DESTINATION_IS_SELECTED', 1);
define('TRANSACTION_SHIPPING_INFOS_ARE_SETTED', 2);
define('TRANSACTION_IS_OPEN', 3);
define('TRANSACTION_CATEGORY_IS_SELECTED', 4);
define('TRANSACTION_WAS_PROCEED', 5);

/**
 * Class SessionTransaction
 * Représente une transaction au sein du site.
 * Cette classe encapsule les règles d'affaires.
 */
class SessionTransaction
{
    const CART_IDENTIFIER = '_CART_';
    const STORE_IDENTIFIER = '_STORE_';
    const ORDER_IDENTIFIER = '_ORDER_';
    const LINES_IDENTIFIER = '_LINES_';
    const STATUS_IDENTIFIER = '_STATUS_';
    const RECEIVER_IDENTIFIER = '_RECEIVER_';
    const CATEGORY_IDENTIFIER = '_CATEGORY_';
    const DESTINIATION_IDENTIFIER = '_DESTINATION_';
    const SHIPPING_ADDRESS_IDENTIFIER = '_SHIPPING_ADDRESS_';

    /**
     * Initialise la transaction.
     */
    public function __construct()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION[self::STATUS_IDENTIFIER])) {
            $_SESSION[self::STATUS_IDENTIFIER] = TRANSACTION_IS_READY;
        }

        if (!isset($_SESSION[self::CART_IDENTIFIER])) {
            $_SESSION[self::CART_IDENTIFIER] = new SessionCart();
        }

        if (!isset($_SESSION[self::LINES_IDENTIFIER])) {
            $_SESSION[self::LINES_IDENTIFIER] = array();
        }
    }


    /**
     * Retourne un tableau contenant les éléments de la transaction.
     * @return array
     */
    public function getArray()
    {
        $array = array();

        switch ($this->getStatus()) {
            case TRANSACTION_WAS_PROCEED:
                $array['order'] = $this->getOrder()->getArray();

                $array['lines'] = array();
                foreach ($this->getLines() as $line) {
                    $array['lines'][] = $line->getArray();
                }

            case TRANSACTION_CATEGORY_IS_SELECTED:
            case TRANSACTION_IS_OPEN:
            case TRANSACTION_SHIPPING_INFOS_ARE_SETTED:
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
        if ($this->getStatus() != TRANSACTION_IS_READY) {
            throw new Exception('The destination is already setted.');
        }

        if (!$destination->isAttached()) {
            throw new Exception('The destination must be attached to a database.');
        }

        $_SESSION[self::DESTINIATION_IDENTIFIER] = $destination;

        $this->setStatus(TRANSACTION_DESTINATION_IS_SELECTED);
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
        // On accepte de redéfinir les informations au cas ou l'utilisateur voudrait les modifiées.
        if ($this->getStatus() != TRANSACTION_DESTINATION_IS_SELECTED &&
            $this->getStatus() != TRANSACTION_SHIPPING_INFOS_ARE_SETTED
        ) {
            throw new Exception('You cannot modify the shipping informations when the transaction is open.');
        }

        $_SESSION[self::STORE_IDENTIFIER] = $store;
        $_SESSION[self::RECEIVER_IDENTIFIER] = $receiver;
        $_SESSION[self::SHIPPING_ADDRESS_IDENTIFIER] = $shippingAddress;

        $this->setStatus(TRANSACTION_SHIPPING_INFOS_ARE_SETTED);
    }

    /**
     * Fige les informations d'expédition de la transaction.
     * @throws Exception
     */
    public function Open()
    {
        if ($this->getStatus() != TRANSACTION_SHIPPING_INFOS_ARE_SETTED) {
            throw new Exception('The shipping informations must be previously setted.');
        }

        $this->setStatus(TRANSACTION_IS_OPEN);
    }

    /**
     * Définit le type de produit choisi par le client. (ex: coussin).
     * @param Category $category
     * @throws Exception
     */
    public function setCategory(Category $category)
    {
        if ($this->getStatus() != TRANSACTION_IS_OPEN) {
            throw new Exception('The transaction must be previously open.');
        }

        if (!$category->isAttached()) {
            throw new Exception('The category must be attached to a database.');
        }

        $_SESSION[self::CATEGORY_IDENTIFIER] = $category;

        $this->setStatus(TRANSACTION_CATEGORY_IS_SELECTED);
    }


    /**
     * Finalise la transaction.
     * @throws Exception
     */
    public function Proceed()
    {
        if ($this->getStatus() != TRANSACTION_CATEGORY_IS_SELECTED) {
            throw new Exception('The category must be previously setted.');
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
                $order->getId(),
                $item->getProduct()->getId(),
                $item->getQuantity(),
                $item->getSerial()
            );

            $_SESSION[self::LINES_IDENTIFIER][] = Lines::Attach($line);
        }

        $this->setStatus(TRANSACTION_WAS_PROCEED);
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
     * Retourne la destination choisie par le client.
     * @return mixed
     * @throws Exception
     */
    public function getDestination()
    {
        if ($this->getStatus() < TRANSACTION_DESTINATION_IS_SELECTED) {
            throw new Exception('The destination must be previously setted.');
        }

        return $_SESSION[self::DESTINIATION_IDENTIFIER];
    }


    /**
     * Retourne la catégorie choisie par le client.
     * @return mixed
     * @throws Exception
     */
    public function getCategory()
    {
        if ($this->getStatus() < TRANSACTION_CATEGORY_IS_SELECTED) {
            throw new Exception('The category must be previously setted.');
        }

        return $_SESSION[self::CATEGORY_IDENTIFIER];
    }


    /**
     * Retourne l'adresse d'expédition.
     * @return mixed
     * @throws Exception
     */
    public function getShippingAddress()
    {
        if ($this->getStatus() < TRANSACTION_SHIPPING_INFOS_ARE_SETTED) {
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
        if ($this->getStatus() < TRANSACTION_SHIPPING_INFOS_ARE_SETTED) {
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
        if ($this->getStatus() < TRANSACTION_SHIPPING_INFOS_ARE_SETTED) {
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
        if ($this->getStatus() < TRANSACTION_CATEGORY_IS_SELECTED) {
            throw new Exception('The category must be previously setted.');
        }

        return $_SESSION[self::CART_IDENTIFIER];
    }

    /**
     * Ajoute un item à la transaction.
     * @param IItem $item
     * @return mixed
     * @throws Exception
     */
    public function AddItem(IItem $item)
    {
        if ($this->getStatus() < TRANSACTION_CATEGORY_IS_SELECTED) {
            throw new Exception('The category must be previously setted.');
        }

        return $this->getCart()->Add($item);
    }


    /**
     * Retire un item de la transaction.
     * @param IItem $item
     * @return mixed
     * @throws Exception
     */
    public function RemoveItem(IItem $item)
    {
        if ($this->getStatus() < TRANSACTION_CATEGORY_IS_SELECTED) {
            throw new Exception('The category must be previously setted.');
        }

        return $this->getCart()->Remove($item);
    }


    /**
     * Retourne la commande de la transaction.
     * @return mixed
     * @throws Exception
     */
    public function getOrder()
    {
        if ($this->getStatus() < TRANSACTION_WAS_PROCEED) {
            throw new Exception('The transaction must be proceed.');
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
        if ($this->getStatus() < TRANSACTION_WAS_PROCEED) {
            throw new Exception('The transaction must be proceed.');
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