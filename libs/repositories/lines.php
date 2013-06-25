<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/line.php');

class Lines
{
    public static function Attach(Line $line)
    {
        $query = 'EXEC [addLine]';
        $query .= '@orderId = "' . $line->getOrderId() . '", ';
        $query .= '@productId = "' . $line->getProductId() . '", ';
        $query .= '@quantity = "' . $line->getQuantity() . '", ';
        $query .= '@serial = "' . $line->getSerial() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The line wasn\'t added.');
        }

        $line->setSku($rows[0]['sku']);
        $line->setModel($rows[0]['model']);
        $line->setFinish($rows[0]['finish']);
        $line->setFabric($rows[0]['fabric']);
        $line->setFrame($rows[0]['frame']);
        $line->setCushion($rows[0]['cushion']);

        return $line;
    }

    public static function FilterByOrderId($id)
    {
        $query = 'EXEC [getLinesByOrderId]';
        $query .= '@orderId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $lines = array();
        foreach ($rows as $row) {
            $line = new Line(
                $row['orderId'],
                $row['productId'],
                $row['quantity'],
                $row['serial']
            );

            $line->setSku($row['sku']);
            $line->setModel($row['model']);
            $line->setFinish($row['finish']);
            $line->setFabric($row['fabric']);
            $line->setFrame($row['frame']);
            $line->setCushion($row['cushion']);

            $lines[] = $line;
        }
        return $lines;
    }
}