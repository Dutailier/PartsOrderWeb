<?php


class State
{
    private $id;
    private $countryId;
    private $name;

    public function __construct($id, $countryId, $name)
    {
        $this->id = $id;
        $this->countryId = $countryId;
        $this->name = $name;
    }

    public static function getStates(Country $country)
    {

        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getStates]("' . $country->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $states = array();
                while (odbc_fetch_row($result)) {
                    $states[] = new State(
                        odbc_result($result, 'id'),
                        $country->getId(),
                        odbc_result($result, 'name'));
                }
                return $states;
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCountryId()
    {
        return $this->countryId;
    }
}