<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');

/**
 * Class Address
 * Représente une adresse.
 */
class Address
{
    private $id;
    private $details;
    private $city;
    private $zip;
    private $stateId;


    /**
     * Le constructeur par défaut.
     * @param $id
     * @param $details
     * @param $city
     * @param $zip
     * @param $stateId
     */
    public function __construct($id, $details = null, $city = null, $zip = null, $stateId = null)
    {
        $this->details = $details;
        $this->city = $city;
        $this->zip = $zip;
        $this->stateId = $stateId;
    }

    /**
     * Insère une adresse dans la base de données et retourne son instance.
     * @param $details
     * @param $city
     * @param $zip
     * @param $stateId
     * @return Address
     * @throws Exception
     */
    public function insert($details, $city, $zip, $stateId)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec(
                $conn,
                '{CALL [BruPartsOrderDb].[dbo].[insertCustomer](' .
                    $details . ', ' .
                    $city . ', ' .
                    $zip . ', ' .
                    $stateId . ')}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                // Récupère la première ligne résultante.
                $row = odbc_fetch_row($result);

                return new Address(
                    $row['id'], $details, $city, $zip, $stateId);
            }
        }
    }

    /**
     * Retourne l'indentifiant de l'adresse.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne les détails de l'adresse (ex: 299, rue Chaput).
     * @return mixed
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Retourne la ville de l'adresse.
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Retourne le code postal américan de l'adresse (ex: 11111).
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Retourne l'identifiant de l'état/province de l'adresse.
     * @return mixed
     */
    public function getStateId()
    {
        return $this->stateId;
    }
}