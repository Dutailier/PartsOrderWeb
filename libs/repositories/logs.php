<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/log.php');

class Logs
{
    public static function FilterByOrderId($id)
    {
        $query = 'EXEC [getLogsByOrderId]';
        $query .= '@orderId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $logs = array();
        foreach ($rows as $row) {

            $log = new Log(
                $row['orderId'],
                $row['userId'],
                $row['event'],
                $row['datetime']
            );
            $log->setId($row['id']);

            $logs[] = $log;
        }
        return $logs;
    }

    public static function Top($number)
    {
        $query = 'EXEC [getTopLogs]';
        $query .= '@number = "' . intval($number) . '"';

        $rows = Database::Execute($query);

        $logs = array();
        foreach ($rows as $row) {

            $log = new Log(
                $row['orderId'],
                $row['userId'],
                $row['event'],
                $row['datetime']
            );
            $log->setId($row['id']);

            $logs[] = $log;
        }
        return $logs;
    }
}