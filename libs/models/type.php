<?php

include_once('config.php');
include_once(ROOT . 'libs/models/category.php');
include_once(ROOT . 'libs/database.php');

/**
 * Class Type
 * Représente un type de pièce.
 */
class Type
{
    private $id;
    private $name;
    private $description;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param $name
     * @param $description
     */
    public function __construct($id, $name = null, $description = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * Retourne les types de pièce disponible pour cette catégorie.
     * @param Category $category
     * @return array
     * @throws Exception
     */
    public static function getTypes(Category $category)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getTypes]("' . $category->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $types = array();
                while (odbc_fetch_row($result)) {
                    $types[] = new Type(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name'),
                        odbc_result($result, 'description')
                    );
                }
                return $types;
            }
        }
    }

    /**
     * Retourne l'identifiant du type de pièce.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le nom du type de pièce.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne la description du type de pièce.
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
}