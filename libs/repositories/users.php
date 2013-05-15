<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/user.php');

class Users
{
    public static function FindByUsernameAndPassword($username, $password)
    {
        $query = 'EXEC [getUserByUsernameAndPassword]';
        $query .= '@username = "' . $username . '", ';
        $query .= '@password = "' . $password . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No user found.');
        }
        return new User(
            $rows[0]['id'],
            $rows[0]['username']);
    }

    public static function Find($id)
    {
        $query = 'EXEC [getUserById]';
        $query .= '@id = "' . $id . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No user found.');
        }
        return new User(
            $rows[0]['id'],
            $rows[0]['username']);
    }
}