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

    /**
     * Constructeur par défaut.
     * @param $userId
     * @param $name
     * @param $phone
     * @param $email
     */
    public function __construct($userId, $name = null, $phone = null, $email = null)
    {
        $this->userId = $userId;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
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
            $_SESSION['retailer'] = getRetailer(User::getConnected());
        }

        return $_SESSION['retailer'];
    }

    /**
     * Retourne l'instance du détaillant correspondant à cet utilisateur.
     * @param User $user
     * @return Retailer
     * @throws Exception
     */
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
                    $row['name'],
                    $row['phone'],
                    $row['email']);
            }
        }
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
        return $this->phone;
    }

    /**
     * Retourne l'adresse courriel du client.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}