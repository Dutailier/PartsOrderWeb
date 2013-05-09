<?php
include_once('config.php');
include(ROOT . 'libs/entities/role.php');

class Roles
{
    public static function IsInRoleName($name)
    {

        if (!Security::isAuthenticated()) {
            throw new Exception('An user must be authenticated.');
        } else {

            // Si les rôles n'ont jamais été récupérés, on
            // en fait la requête et on garde le résultat pour de
            // futures utilistions.
            if (!isset($_SESSION['roles'])) {
                $user = Users::getConnected();
                $roles = Roles::FilterByUserId($user->getId());
                $_SESSION['roles'] = $roles;
            }

            $index = self::getIndex($_SESSION['roles'], $name);

            return $index != -1;
        }
    }

    public static function FilterByUserId($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            // Exécute la procédure stockée.
            $result = odbc_exec($conn, '{CALL [BruPartsOrderDb].[dbo].[getRoles]("' . $id . '")}');

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

    private static function getIndex(Array $roles, $name)
    {
        foreach ($roles as $index => $role) {
            if (strtolower($role->getName()) == strtolower($name)) {
                return $index;
            }
        }

        return -1;
    }
}