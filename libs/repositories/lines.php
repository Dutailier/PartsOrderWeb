<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/line.php');

class Lines
{
    public static function Attach(Line $line)
    {
        $query = 'EXEC [AddLine]';
        $query .= '@orderId = "' . $line->getOrderId() . '", ';
        $query .= '@productId = "' . $line->getProductId() . '", ';
        $query .= '@quantity = "' . $line->getQuantity() . '", ';
        $query .= '@serial = "' . $line->getSerial() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The line wasn\'t added.');
        }

        $line->setSku($rows[0]['sku']);

        return $line;
    }

    public static function FilterByOrderId($id)
    {
        $query = 'EXEC [getLinesByOrderId]';
        $query .= '@orderId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $lines = array();
        foreach ($rows as $row) {
            $lines[] = new Line(
                $row['orderId'], $row['productId'], $row['quantity'], $row['serial'], $row['sku']
            );
        }
        return $lines;
    }
}