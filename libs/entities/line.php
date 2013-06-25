<?php

include_once(ROOT . 'libs/entity.php');
include_once(ROOT . 'libs/repositories/orders.php');
include_once(ROOT . 'libs/repositories/products.php');

/**
 * Class Line
 * Représente une ligne dans une commande.
 */
class Line extends Entity
{
    const REGEX_SERIAL = '/^[0-9]{11}$/';
    private $orderId;
    private $productId;
    private $serial;
    private $quantity;
    private $sku;
    private $model;
    private $finish;
    private $fabric;
    private $frame;
    private $cushion;


    /**
     * Initialise la ligne de commande.
     * @param $orderId
     * @param $productId
     * @param $quantity
     * @param $serial
     */
    function __construct($orderId, $productId, $quantity, $serial)
    {
        $this->setOrderId($orderId);
        $this->setProductId($productId);
        $this->setQuantity($quantity);
        $this->setSerial($serial);
    }


    /**
     * Retourne un tableau contenant les informations de la ligne de commande.
     * @return array|mixed
     */
    public function getArray()
    {
        return array(
            'orderId' => $this->getOrderId(),
            'product' => $this->getProduct()->getArray(),
            'serial' => $this->getSerial(),
            'quantity' => $this->getQuantity(),
            'sku' => $this->getSku(),
            'model' => $this->getModel(),
            'finish' => $this->getFinish(),
            'fabric' => $this->getFabric(),
            'frame' => $this->getFrame(),
            'cushion' => $this->getCushion(),
        );
    }

    /**
     * Définit l'identifiant de la commande.
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = intval($orderId);
    }

    /**
     * Retourne l'identifiant de la commande.
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Définit l'identifiant du produit.
     * @param mixed $productId
     */
    public function setProductId($productId)
    {
        $this->productId = intval($productId);
    }

    /**
     * Retourne l'identifiant du produit.
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Définit la quantité de la ligne de commande.
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = intval($quantity);
    }

    /**
     * Retourne la quantité de la ligne de commande.
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Définit le numéro de série de la ligne de commande.
     * @param $serial
     * @throws Exception
     */
    public function setSerial($serial)
    {
        if (!preg_match(self::REGEX_SERIAL, $serial)) {
            throw new Exception('The serial number must be 11 digits.');
        }

        $this->serial = $serial;
    }

    /**
     * Retourne le numéro de série de la ligne de commande.
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * Définit le numéro d'ensemble de la ligne de commande.
     * @param $sku
     * @throws Exception
     */
    public function setSku($sku)
    {
        if (strlen($sku) > 12) {
            throw new Exception('The length of the sku is too long.');
        }

        $this->sku = $sku;
    }

    /**
     * Retourne le numéro d'ensemble de la ligne de commande.
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * Définit le numéro de modèle de la ligne de comande.
     * @param mixed $model
     */
    public function setModel($model)
    {
        if (strlen($model) > 6) {
            throw new Exception('The length of the model is too long.');
        }

        $this->model = $model;
    }

    /**
     * Retourne le numéro de modèle de la ligne de commande.
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Définit le numéro de fini de la ligne de commande.
     * @param mixed $finish
     */
    public function setFinish($finish)
    {
        if (strlen($finish) > 2) {
            throw new Exception('The length of the finish is too long.');
        }

        $this->finish = $finish;
    }

    /**
     * Retourne le numéro de fini de la ligne de commande.
     * @return mixed
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * Définit le numéro de tissu de la ligne de commande.
     * @param $fabric
     * @throws Exception
     */
    public function setFabric($fabric)
    {
        if (strlen($fabric) > 4) {
            throw new Exception('The length of the fabric is too long.');
        }

        $this->fabric = $fabric;
    }

    /**
     * Retourne le numéro de tissu de la ligne de commande.
     * @return mixed
     */
    public function getFabric()
    {
        return $this->fabric;
    }

    /**
     * Définit le numéro squelette de la ligne de commande.
     * @param mixed $frame
     */
    public function setFrame($frame)
    {
        if (strlen($frame) > 12) {
            throw new Exception('The length of the frame is too long.');
        }

        $this->frame = $frame;
    }

    /**
     * Retourne le numéro de squelette de la ligne de commande.
     * @return mixed
     */
    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * Définit le numéro de coussin de la ligne de commande.
     * @param $cushion
     * @throws Exception
     */
    public function setCushion($cushion)
    {
        if (strlen($cushion) > 12) {
            throw new Exception('The length of the cushion is too long.');
        }

        $this->cushion = $cushion;
    }

    /**
     * Retourne le numéro de coussin de la ligne de commande.
     * @return mixed
     */
    public function getCushion()
    {
        return $this->cushion;
    }


    /**
     * Retourne la commande.
     * @return Order
     */
    public function getOrder()
    {
        return Orders::Find($this->getOrderId());
    }


    /**
     * Retourne le produit.
     * @return Product
     */
    public function getProduct()
    {
        return Products::Find($this->getProductId());
    }
}