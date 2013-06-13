<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/user.php');

class Users
{
    public static function Attach(User $user)
    {
        $query = 'EXEC [addUser]';
        $query .= '@username = "' . $user->getUsername() . '", ';
        $query .= '@password = "' . sha1($user->getUsername() . $user->getUsername()) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The address wasn\'t added.');
        }

        $user->setId($rows[0]['id']);

        return $user;
    }

    public static function FindByUsernameAndPassword($username, $password)
    {
        $query = 'EXEC [getUserByUsernameAndPassword]';
        $query .= '@username = "' . $username . '", ';
        $query .= '@password = "' . $password . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('Username or password incorrect.');
        }

        $user = new User(
            $rows[0]['username']
        );
        $user->setId($rows[0]['id']);

        return $user;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getUserById]';
        $query .= '@id = "' . $id . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No user found.');
        }

        $user = new User(
            $rows[0]['username']
        );
        $user->setId($rows[0]['id']);

        return $user;
    }
}