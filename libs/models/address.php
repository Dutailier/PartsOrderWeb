<?php

include_once('config.php');
include_once(ROOT . 'libs/models/state.php');
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
    private $state;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param null $details
     * @param null $city
     * @param null $zip
     * @param State $state
     */
    public function __construct($id, $details = null, $city = null, $zip = null, State $state = null)
    {
        $this->id = $id;
        $this->details = $details;
        $this->city = $city;
        $this->zip = $zip;
        $this->state = $state;
    }

    /**
     * Insère une adresse dans la base de données et retourne son instance.
     * @param $details
     * @param $city
     * @param $zip
     * @param State $state
     * @return Address
     * @throws Exception
     */
    public static function Add($details, $city, $zip, State $state)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[insertAddress]("' .
                $details . '", "' .
                $city . '", "' .
                $zip . '", "' .
                $state->getId() . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Address(odbc_result($result, 'id'), $details, $city, $zip);
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
        if (is_null($this->details)) {
            $this->Fill();
        }

        return $this->details;
    }

    /**
     * Récupère les informations de la présente adresse.
     * @throws Exception
     */
    private function Fill()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getAddress]("' .
                $this->id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                $this->id = odbc_result($result, 'id');
                $this->details = odbc_result($result, 'details');
                $this->city = odbc_result($result, 'city');
                $this->zip = odbc_result($result, 'zip');
                $this->state = new State(odbc_result($result, 'state_id'));
            }
        }
    }

    /**
     * Retourne la ville de l'adresse.
     * @return mixed
     */
    public function getCity()
    {
        if (is_null($this->city)) {
            $this->Fill();
        }

        return $this->city;
    }

    /**
     * Retourne le code postal américan de l'adresse (ex: 11111).
     * @return mixed
     */
    public function getZip()
    {
        if (is_null($this->zip)) {
            $this->Fill();
        }

        return $this->zip;
    }

    /**
     * Retourne l'instance de l'état/province de cette adresse.
     * @return State
     */
    public function getState()
    {
        if(is_null($this->state)) {
            $this->Fill();
        }

        return $this->state;
    }
}