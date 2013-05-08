<?php

include_once('config.php');
include_once(ROOT . 'libs/models/state.php');
include_once(ROOT . 'libs/database.php');

/**
 * Class Country
 * Représente un pays.
 */
class Country
{
    private $id;
    private $name;
    private $states;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Retourne la liste des pays.
     * @return array
     * @throws Exception
     */
    public static function getCountries()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getCountries]}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $countries = array();
                // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
                while (odbc_fetch_row($result)) {
                    $countries[] = new Country(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name'));
                }

                return $countries;
            }
        }
    }

    /**
     * Retourne les états/provinces de ce pays.
     * @return array
     */
    public function getStates()
    {
        if (is_null($this->states)) {
            $this->states = State::getStates($this);
        }

        return $this->states;
    }

    /**
     * Retourne l'identifiant de ce pays.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le nom de ce pays.
     * @return mixed
     */
    public function getName()
    {
        if (is_null($this->name)) {
            $this->Fill();
        }

        return $this->name;
    }

    private function Fill()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getCountry]("' .
                $this->id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                $this->id = odbc_result($result, 'id');
                $this->name = odbc_result($result, 'name');
            }
        }
    }
}