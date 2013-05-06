<?php

include_once('config.php');
include_once(ROOT . 'libs/models/retailer.php');
include_once(ROOT . 'libs/models/customer.php');
include_once(ROOT . 'libs/models/part.php');
include_once(ROOT . 'libs/database.php');

/**
 * Class Order
 * Représente une commande de pièces.
 */
class Order
{
    private $id;
    private $parts;

    /**
     * Constructeur par défaut.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Place une commande et retourne l'instance de celle-ci.
     * @param Retailer $retailer
     * @param Customer $customer
     * @return Order
     * @throws Exception
     */
    public static function Place(Retailer $retailer, Customer $customer)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec(
                $conn,
                '{CALL [BruPartsOrderDb].[dbo].[placeOrder]("' .
                    $customer->getId() . '", "' .
                    $retailer->getUserId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Order(odbc_result($result, 'id'));
            }
        }
    }

    /**
     * Ajouter l'item à la commande.
     * @param CartItem $item
     * @throws Exception
     */
    public function AddItem(CartItem $item)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[insertPartIntoOrder]("' .
                $item->getTypeId() . '", "' .
                $item->getSerialGlider() . '", "' .
                $this->getId() . '", "' .
                $item->getQuantity() . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {
                $this->parts[] = $item;
            }
        }
    }

    /**
     * Retourne l'identifiant de la commande.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}