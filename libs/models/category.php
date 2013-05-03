<?php

include_once('config.php');
include_once(ROOT . 'libs/models/type.php');
include_once(ROOT . 'libs/database.php');

/**
 * Class Category
 * Représente une catégorie de pièces.
 */
class Category
{
    private $id;
    private $name;
    private $types;

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
     * Retourne toutes les catégories disponibles.
     * @return array
     * @throws Exception
     */
    public static function getCategories()
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getCategories]}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $categories = array();
                while (odbc_fetch_row($result)) {
                    $categories[] = new Category(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name')
                    );
                }
                return $categories;
            }
        }
    }

    /**
     * Retourne l'identifiant de la catégorie.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la catégorie.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne les types de pièce de la catégorie.
     * @return array
     */
    public function getTypes()
    {
        if (is_null($this->types)) {
            $this->types = Type::getTypes($this);
        }

        return $this->types;
    }
}