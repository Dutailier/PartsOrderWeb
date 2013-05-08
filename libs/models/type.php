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
    private $category;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param null $name
     * @param null $description
     * @param Category $category
     */
    public function __construct($id, $name = null, Category $category = null, $description = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
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
                        $category,
                        odbc_result($result, 'description')
                    );
                }
                return $types;
            }
        }
    }

    /**
     * Retourne le nom du type de pièce.
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

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getType]("' .
                $this->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                $this->id = odbc_result($result, 'id');
                $this->name = odbc_result($result, 'name');
                $this->description = odbc_result($result, 'description');
                $this->category = new Category(odbc_result($result, 'category_id'));
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
     * Retourne la description du type de pièce.
     * @return mixed
     */
    public function getDescription()
    {
        if (is_null($this->description)) {
            $this->Fill();
        }

        return $this->description;
    }

    /**
     * Retourne l'instance de la catégorie de ce type.
     * @return Category
     */
    public function getCategory()
    {
        if (is_null($this->category)) {
            $this->Fill();
        }

        return $this->category;
    }
}