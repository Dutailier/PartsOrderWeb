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
                $row['username'],
                $row['event'],
                $row['datetime']
            );
            $log->setId($row['id']);

            $logs[] = $log;
        }
        return $logs;
    }

    public static function FilterByStoreId($id)
    {
        $query = 'EXEC [getLogsByStoreId]';
        $query .= '@storeId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $logs = array();
        foreach ($rows as $row) {

            $log = new Log(
                $row['orderId'],
                $row['username'],
                $row['event'],
                $row['datetime']
            );
            $log->setId($row['id']);

            $logs[] = $log;
        }
        return $logs;
    }

    public static function FilterByRangeOfDates($from, $to)
    {
        $query = 'EXEC [getLogsByRangeOfDates]';
        $query .= '@from = "' . date('Ymd', $from) . '", ';
        $query .= '@to = "' . date('Ymd', $to) . '"';

        $rows = Database::Execute($query);

        $logs = array();
        foreach ($rows as $row) {

            $log = new Log(
                $row['orderId'],
                $row['username'],
                $row['event'],
                $row['datetime']
            );
            $log->setId($row['id']);

            $logs[] = $log;
        }
        return $logs;
    }

    public static function FilterByRangeOfDatesAndStoreId($from, $to, $storeId)
    {
        $query = 'EXEC [getLogsByRangeOfDatesAndStoreId]';
        $query .= '@from = "' . date('Ymd', $from) . '", ';
        $query .= '@to = "' . date('Ymd', $to) . '", ';
        $query .= '@storeId = "' . intval($storeId) . '"';

        $rows = Database::Execute($query);

        $logs = array();
        foreach ($rows as $row) {

            $log = new Log(
                $row['orderId'],
                $row['username'],
                $row['event'],
                $row['datetime']
            );
            $log->setId($row['id']);

            $logs[] = $log;
        }
        return $logs;
    }
}