<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/models/user.php');

/**
 * Class Role
 * Gère les méthodes relatives aux rôles des utilisteurs.
 */
class Role
{
    private $id;
    private $name;

    /**
     * Constructeur par défaut.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Retourne l'id du rôle.
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Retourne le nom du rôle.
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Retourne vrai si l'utilisateur connecté a le rôle spécifié.
     * @param $name
     * @return bool
     * @throws Exception
     */
    public static function IsInRoleName($name)
    {

        if (!Security::isAuthenticated()) {
            throw new Exception('An user must be authenticated.');
        } else {

            // Si les rôles n'ont jamais été récupérés, on
            // en fait la requête et on garde le résultat pour de
            // futures utilistions.
            if (is_null($_SESSION['roles'])) {
                $_SESSION['roles'] = Role::getRoles(User::getConnected());
            }

            $index = Role::getIndex($_SESSION['roles'], $name);

            return $index != -1;
        }
    }

    /**
     * Retourne la liste des rôles de l'utilisteur.
     * @param User $user
     * @return mixed
     * @throws Exception
     */
    public static function getRoles(User $user)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getRoles]("' . $user->getId() . '")}');

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                $roles = array();
                while (odbc_fetch_row($result)) {
                    $roles[] = new Role(
                        odbc_result($result, 'id'),
                        odbc_result($result, 'name')
                    );
                }
                return $roles;
            }
        }
    }

    /**
     * Retourne l'index du rôle si celui-ci figure dans la liste ou
     * retourne le nombre de rôles contenus dans la liste.
     * @param array $roles
     * @param $name
     * @return int
     */
    private static function getIndex(Array $roles, $name)
    {
        foreach ($roles as $index => $role) {
            if ($role->getName() == $name) {
                return $index;
            }
        }

        return -1;
    }
}