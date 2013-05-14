<?php

include_once('config.php');
include_once(ROOT . 'libs/Database.php');
include_once(ROOT . 'libs/entities/User.php');

class Users
{
    public static function FindByUsernameAndPassword($username, $password)
    {
        $query = 'EXEC [getUserByUsernameAndPassword]';
        $query .= '@Username = ' . $username . ' , ';
        $query .= '@Password = ' . $password;

        $row = Database::Execute($query);

        return new User(
            $row['Id'],
            $row['Username']);
    }
}