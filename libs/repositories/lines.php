<?php

include_once('config.php');
include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/line.php');

class Lines
{
    public static function Add($orderId, $partId, $categoryId, $serial, $quantity)
    {
        if (!preg_match(Line::REGEX_SERIAL, $serial)) {
            throw new Exception('The serial must be 11 digits.');
        }

        $query = 'EXEC [addLine]';
        $query .= '@orderId = "' . intval($orderId) . '", ';
        $query .= '@partId = "' . intval($partId) . '", ';
        $query .= '@categoryId = "' . intval($categoryId) . '", ';
        $query .= '@serial = "' . $serial . '", ';
        $query .= '@quantity = "' . intval($quantity) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The line wasn\'t added.');
        }

        return new Line(
            $rows[0]['id'],
            $orderId,
            $partId,
            $categoryId,
            $serial,
            $rows[0]['sku'],
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
                $row['categoryId'],
                $row['serial'],
                $row['sku'],
                $row['quantity']
            );
        }
        return $lines;
    }
}