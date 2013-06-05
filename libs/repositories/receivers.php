<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/receiver.php');

class Receivers
{
    public static function Attach(Receiver $receiver)
    {
        $query = 'EXEC [addReceiver]';
        $query .= '@name = "' . $receiver->getName() . '", ';
        $query .= '@phone = "' . $receiver->getPhone() . '", ';
        $query .= '@email = "' . $receiver->getEmail() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The address wasn\'t added.');
        }

        $receiver->setId($rows[0]['id']);

        return $receiver;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getReceiverById]';
        $query .= '@id = ' . intval($id);

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No customer found.');
        }

        $receiver = new Receiver(
            $rows[0]['name'],
            $rows[0]['phone'],
            $rows[0]['email']
        );
        $receiver->setId($rows[0]['id']);

        return $receiver;
    }
}