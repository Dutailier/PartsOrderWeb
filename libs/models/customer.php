<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');

/**
 * Class Customer
 * Représente un client.
 */
class Customer
{
    private $id;
    private $firstname;
    private $lastname;
    private $phone;
    private $email;

    /**
     * Le constructeur par défaut.
     * @param $id
     * @param $firstname
     * @param $lastname
     * @param $phone
     * @param $email
     */
    public function __construct(
        $id, $firstname = null, $lastname = null, $phone = null, $email = null)
    {
        $this->id = trim($id);
        $this->firstname = trim($firstname);
        $this->lastname = trim($lastname);
        $this->phone = preg_replace('/[^\d]/', '', $phone);
        $this->email = trim($email);
    }

    /**
     * Insère un client dans la base de données et retourne son instance.
     * @param $firstname
     * @param $lastname
     * @param $phone
     * @param $email
     * @param Address $address
     * @throws Exception
     * @return Customer
     */
    public static function Add($firstname, $lastname, $phone, $email, Address $address)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[insertCustomer]("' .
                $address->getId() . '", "' .
                $firstname . '", "' .
                $lastname . '", "' .
                $phone . '", "' .
                $email . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Customer(
                    odbc_result($result, 'id'),
                    $firstname,
                    $lastname,
                    $phone,
                    $email);
            }
        }
    }

    /**
     * Retourne l'identifiant du client.
     * @return string
     */
    public
    function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le prénom du client.
     * @return string
     */
    public
    function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Retourne le nom du client.
     * @return string
     */
    public
    function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Retourne le numéro de téléhpone du client (ex: 14504647981).
     * @return mixed
     */
    public
    function getPhone()
    {
        return $this->phone;
    }

    /**
     * Retourne l'adresse courriel du client.
     * @return string
     */
    public
    function getEmail()
    {
        return $this->email;
    }
}