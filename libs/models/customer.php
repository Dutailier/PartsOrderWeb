<?php


class Customer
{

    private $id;
    private $address_id;
    private $firstname;
    private $lastname;
    private $phone;
    private $email;

    public function __construct(
        $id, $address_id, $firstname, $lastname, $phone, $email)
    {
        $this->id = $id;
        $this->address_id = $address_id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phone = $phone;
        $this->email = $email;
    }

    public function insert($address_id, $firstname, $lastname, $phone, $email)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[insertCustomer](' .
                $address_id . ', ' . $firstname . ', ' . $lastname . ', ' . $phone . ', ' . $email . ')}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                // Récupère la première ligne résultante.
                $row = odbc_fetch_row($result);

                return new Customer(
                    $row['id'], $address_id, $firstname, $lastname, $phone, $email);
            }
        }
    }
}