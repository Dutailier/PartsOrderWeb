<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/user.php');

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
     * Retourne vrai si le nm de rôle spécifié est identifique à celui-ci.
     * @param $name
     * @return bool
     */
    public function CompareName($name)
    {
        return $this->name == $name;
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
            if (empty($_SESSION['roles'])) {
                $_SESSION['roles'] = Role::getRoles(Security::getUserConnected());
            }

            $i = Role::getIndex($_SESSION['roles'], $name);

            // Si l'index est inférieur au nombre de rôles,
            // c'est que l'utilisateur a ce rôle.
            if ($i < count($_SESSION['roles'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Retourne la liste des rôles de l'utilisteur.
     * @param User $user
     * @return mixed
     * @throws Exception
     */
    private static function getRoles(User $user)
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
                $i = 0;

                // Inscrire chaque ligne dans l'objet JSON qui sera retourné.
                while (odbc_fetch_row($result)) {
                    $roles[$i]['id'] = odbc_result($result, 'id');
                    $roles[$i]['name'] = odbc_result($result, 'name');
                    $i++;
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
        $count = count($roles);

        // Parcours tous les rôles afin de les comparer.
        for ($i = 0; $i < $count; $i++) {
            if ($roles[$i]->CompareName($name)) {
                return $i;
            }
        }
        return $count;
    }
}