<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/subOrder.php');

class SubOrders
{
    public static function Attach(SubOrder $subOrder)
    {
        $query = 'EXEC [addSubOrder]';
        $query .= '@orderId = "' . $subOrder->getOrderId() . '", ';
        $query .= '@destinationId = "' . $subOrder->getDestinationId() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The subOrder wasn\'t added.');
        }

        $subOrder->setId($rows[0]['id']);
        $subOrder->setNumber($rows[0]['number']);
        $subOrder->setStatus($rows[0]['status']);

        return $subOrder;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getSubOrderById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No subOrder found.');
        }

        $subOrder = new SubOrder(
            $rows[0]['orderId'],
            $rows[0]['destinationId'],
            $rows[0]['number'],
            $rows[0]['status']
        );
        $subOrder->setId($rows[0]['id']);

        return $subOrder;
    }

    public static function FilterByOrderId($id)
    {
        $query = 'EXEC [getSubOrderByOrderId]';
        $query .= '@orderId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $subOrders = array();
        foreach ($rows as $row) {

            $order = new Order(
                $row['shippingAddressId'],
                $row['storeId'],
                $row['receiverId'],
                $row['number'],
                $row['creationDate'],
                $row['status']
            );
            $order->setId($row['id']);

            $subOrders[] = $order;
        }
        return $subOrders;
    }
}