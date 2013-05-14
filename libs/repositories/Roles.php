<?php

include_once('config.php');
include_once(ROOT . 'libs/Database.php');
include_once(ROOT . 'libs/entities/Role.php');

class Roles
{
    public static function IsInRoleName($name)
    {
        if (session_id() == '') {
            session_start();
        }

        if (empty($_SESSION['roles'])) {
            $_SESSION['roles'] = self::FilterByUserId($_SESSION['user']->getId());
        }

        foreach ($_SESSION['roles'] as $role) {
            if (strtolower($role->getName()) == strtolower($name)) {
                return true;
            }
        }
        return false;
    }

    public static function FilterByUserId($id)
    {
        $query = 'EXEC [getRolesByUserId]';
        $query .= '@UserId = ' . $id;

        $rows = Database::Execute($query);

        $roles = array();
        foreach ($rows as $row) {
            $roles[] = new Role(
                $row['Id'],
                $row['Name']
            );
        }
        return $roles;
    }
}