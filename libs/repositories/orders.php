<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/order.php');

class Orders
{
    public static function Attach(Order $order)
    {
        $query = 'EXEC [addOrder]';
        $query .= '@shippingAddressId = "' . $order->getShippingAddressId() . '", ';
        $query .= '@storeId = "' . $order->getStoreId() . '", ';
        $query .= '@receiverId = "' . $order->getReceiverId() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The order wasn\'t added.');
        }

        $order->setId($rows[0]['id']);
        $order->setNumber($rows[0]['number']);
        $order->setCreationDate($rows[0]['creationDate']);

        return $order;
    }

    public static function Find($id)
    {
        $query = 'EXEC [getOrderById]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No order found.');
        }

        $order = new Order(
            $rows[0]['shippingAddressId'],
            $rows[0]['storeId'],
            $rows[0]['receiverId'],
            $rows[0]['number'],
            $rows[0]['creationDate'],
            $rows[0]['status']
        );
        $order->setId($rows[0]['id']);

        return $order;
    }

    public static function FilterByRangeOfDates($from, $to)
    {
        $query = 'EXEC [getOrdersByRangeOfDates]';
        $query .= '@from = "' . date('Ymd', $from) . '", ';
        $query .= '@to = "' . date('Ymd', $to) . '"';

        $rows = Database::Execute($query);

        $orders = array();
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

            $orders[] = $order;
        }
        return $orders;
    }

    public static function FilterByRangeOfDatesAndStoreId($from, $to, $id)
    {
        $query = 'EXEC [getOrdersByRangeOfDatesAndStoreId]';
        $query .= '@from = "' . date('Ymd', $from) . '", ';
        $query .= '@to = "' . date('Ymd', $to) . '", ';
        $query .= '@storeId = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        $orders = array();
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

            $orders[] = $order;
        }
        return $orders;
    }
}