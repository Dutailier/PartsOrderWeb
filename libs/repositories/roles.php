<?php

include_once('config.php');
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
            $roles[] = new Role($row['id'], $row['name']);
        }
        return $roles;
    }
}