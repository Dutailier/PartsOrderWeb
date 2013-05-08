<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/models/user.php');

/**
 * Class Retailer
 * Représente un détaillant.
 */
class Retailer
{
    private $userId;
    private $name;
    private $phone;
    private $email;
    private $address;

    /**
     * Constructeur par défaut.
     * @param $userId
     * @param null $name
     * @param null $phone
     * @param null $email
     * @param Address $address
     */
    public function __construct(
        $userId, $name = null, $phone = null,
        $email = null, Address $address = null)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->address = $address;
    }

    /**
     * Retourne le détaillant actuellement connecté.
     * @return mixed
     */
    public static function getConnected()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION['retailer'])) {
            $_SESSION['retailer'] = new Retailer(User::getConnected()->getId());
        }

        return $_SESSION['retailer'];
    }

    /**
     * Retourne l'identifiant du client.
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Retourne le numéro de téléhpone du client (ex: 14504647981).
     * @return mixed
     */
    public function getPhone()
    {
        if (is_null($this->phone)) {
            $this->Fill();
        }

        return $this->phone;
    }

    private function Fill()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getRetailer]("' .
                $this->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                $this->id = odbc_result($result, 'user_id');
                $this->name = odbc_result($result, 'name');
                $this->phone = odbc_result($result, 'phone');
                $this->email = odbc_result($result, 'email');
                $this->address = new Address(odbc_result($result, 'address_id'));
            }
        }
    }

    /**
     * Retourne l'adresse courriel du client.
     * @return string
     */
    public
    function getEmail()
    {
        if (is_null($this->email)) {
            $this->Fill();
        }

        return $this->email;
    }

    /**
     * Retourne l'instance de l'adresse de ce détaillant.
     * @return Address
     */
    public
    function getAddress()
    {
        if (is_null($this->address)) {
            $this->Fill();
        }

        return $this->address;
    }
}