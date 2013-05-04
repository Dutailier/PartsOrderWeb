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
    public function Place(Retailer $retailer, Customer $customer)
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
                    $retailer->getId() . '", "' .
                    $customer->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                // Récupère la première ligne résultante.
                $row = odbc_fetch_row($result);

                return new Order($row['id']);
            }
        }
    }

    /**
     * Ajouter une pièce à la commande.
     * @param Part $part
     * @throws Exception
     */
    public function AddPart(Part $part)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec(
                $conn,
                '{CALL [BruPartsOrderDb].[dbo].[addPart]("' .
                    $part->getId() . '", "' .
                    $this->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                // Récupère la première ligne résultante.
                $row = odbc_fetch_row($result);

                $this->parts[] = $part;
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