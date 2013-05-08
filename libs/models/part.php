<?php

include_once('config.php');
include_once(ROOT . 'libs/cartItem.php');

/**
 * Class Part
 * Représente une pièce.
 */
class Part extends CartItem
{
    private $id;
    private $sku;
    private $serialGlider;
    private $type;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param null $sku
     * @param null $serialGlider
     * @param Type $type
     */
    public function __construct($id, $serialGlider = null, Type $type = null, $sku = null)
    {
        $this->id = $id;
        $this->sku = $sku;
        $this->serialGlider = $serialGlider;
        $this->type = $type;
    }

    /**
     * Retourne le type de la pièce.
     * @return Type
     */
    public function getType()
    {
        if (is_null($this->type)) {
            $this->Fill();
        }

        return $this->type;
    }

    /**
     * Récupère les informations de cette pièce.
     * @throws Exception
     */
    private function Fill()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getPart]("' .
                $this->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                $this->id = odbc_result($result, 'id');
                $this->sku = odbc_result($result, 'sku');
                $this->serialGlider = odbc_result($result, 'serial_glider');
                $this->type = new Type(odbc_result($result, 'type_id'));
            }
        }
    }

    /**
     * Retourne l'identifiant de la pièce.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le SKU de la pièce.
     * @return null
     */
    public function getSku()
    {
        if (is_null($this->sku)) {
            $this->Fill();
        }
        return $this->sku;
    }

    /**
     * Retourne vrai si l'objet passé est identique à cette pièce.
     * @param $object
     * @return bool|mixed
     */
    public function equals($object)
    {
        return $object instanceof self &&
            $object->getId() == $this->getId() &&
            $object->getSerialGlider() == $this->getSerialGlider();
    }

    /**
     * Retourne le numéro de série de chaise nécessitant cette pièce.
     * @return null
     */
    public function getSerialGlider()
    {
        if (is_null($this->serialGlider)) {
            $this->Fill();
        }

        return $this->serialGlider;
    }
}
