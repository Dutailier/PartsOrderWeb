<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/line.php');

class Lines
{
    public static function Add($orderId, $partId, $serial, $quantity)
    {
        if (!preg_match(Line::REGEX_SERIAL, $serial)) {
            throw new Exception('The serial must be 11 digits.');
        }

        $query = 'EXEC [addLine]';
        $query .= '@orderId = "' . intval($orderId) . '", ';
        $query .= '@partId = "' . intval($partId) . '", ';
        $query .= '@serial = "' . $serial . '", ';
        $query .= '@quantity = "' . intval($quantity) . '"';

        $row = Database::Execute($query);

        if (empty($row)) {
            throw new Exception('The line wasn\'t added.');
        }

        return new Line(
            $row['id'],
            $orderId,
            $partId,
            $serial,
            $row['sku'],
            $quantity
        );
    }

    public static function FilterByOrderId($id)
    {
        $query = 'EXEC [getLinesByOrderId]';
        $query .= '@orderId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $lines = array();
        foreach ($rows as $row) {
            $lines[] = new Line(
                $row['orderId'],
                $row['partId'],
                $row['serial'],
                $row['sku'],
                $row['quantity']
            );
        }
        return $lines;
    }
}