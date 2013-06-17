<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/role.php');

class Roles
{
    public static function FilterByUserId($id)
    {
        $query = 'EXEC [getRolesByUserId]';
        $query .= '@userId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $roles = array();
        foreach ($rows as $row) {

            $role = new Role(
                $row['name']
            );
            $role->setId($row['id']);

            $roles[] = $role;
        }
        return $roles;
    }

    public static function addUserToRoleName(User $user, $name)
    {
        if (!$user->isAttached()) {
            throw new Exception('The user must be attached to a database.');
        }

        $query = 'EXEC [addUserToRoleName]';
        $query .= '@userId = "' . $user->getId() . '", ';
        $query .= '@roleName = "' . strtolower($name) . '"';

        Database::Execute($query);
    }
}