<?php


class Tools
{
    public static function validSerial($serial)
    {
        $query = 'EXEC [validSerial]';
        $query .= '@serial = "' . trim($serial) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('Username or password incorrect.');
        }

        return $rows[0]['count'] > 0;
    }
}