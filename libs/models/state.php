<?php

include_once('config.php');
include_once(ROOT . 'libs/models/country.php');
include_once(ROOT . 'libs/database.php');

/**
 * Class State
 * Représente un état/province.
 */
class State
{
    private $id;
    private $name;

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
     * Retourne tous les états/provinces de ce pays.
     * @param Country $country
     * @return array
     * @throws Exception
     */
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
                        odbc_result($result, 'name'));
                }
                return $states;
            }
        }
    }

    /**
     * Retourne l'identifiant de l'état/province.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le nom de l'état/province.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }
}