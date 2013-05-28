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
}