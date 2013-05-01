<?php

include_once('config.php');

class Retailer
{
    private $user_id;
    private $address_id;
    private $name;
    private $phone;
    private $email;

    public function __construct(
        $user_id, $address_id, $name, $phone, $email)
    {
        $this->user_id = $user_id;
        $this->address_id = $address_id;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
    }

    public static function getConnected()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION['retailer'])) {
            $_SESSION['retailer'] = getRetailer(User::getConnected());
        }

        return $_SESSION['retailer'];
    }

    public static function getRetailer(User $user)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getRetailer]("' . $user->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                // Récupère la première ligne résultante.
                $row = odbc_fetch_row($result);

                return new Retailer(
                    $row['user_id'],
                    $row['address_id'],
                    $row['name'],
                    $row['phone'],
                    $row['email']);
            }
        }
    }
}