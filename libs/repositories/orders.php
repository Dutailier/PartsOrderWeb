<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/order.php');

class Orders
{
    public static function Attach(Order $order)
    {
        $query = 'EXEC [addOrder]';
        $query .= '@shippingAddressId = "' . $order->getShippingAddressId() . '", ';
        $query .= '@retailerId = "' . $order->getStoreId() . '"';

        if (!is_null($order->getReceiverId())) {
            $query .= ', @customerId = "' . $order->getReceiverId() . '"';
        }

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The order wasn\'t added.');
        }

        $order->setId($rows[0]['id']);
        $order->setStatus(($rows[0]['status']));
        $order->setCreationDate($rows[0]['creationDate']);

        return $order;
    }

    public static function Confirm($id)
    {
        $query = 'EXEC [confirmOrder]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No order found.');
        }

        $order = new Order(
            $rows[0]['shippingAddressId'],
            $rows[0]['storeId'],
            $rows[0]['receiverId'],
            $rows[0]['creationDate'],
            $rows[0]['status']
        );
        $order->setId($rows[0]['id']);

        return $order;
    }

    public static function Cancel($id)
    {
        $query = 'EXEC [cancelOrder]';
        $query .= '@id = "' . intval($id) . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('No order found.');
        }

        $order = new Order(
            $rows[0]['shippingAddress'],
            $rows[0]['storeId'],
            $rows[0]['receiverId'],
            $rows[0]['creationDate'],
            $rows[0]['status']
        );
        $order->setId($rows[0]['id']);

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
            $rows[0]['shippingAddress'],
            $rows[0]['storeId'],
            $rows[0]['receiverId'],
            $rows[0]['creationDate'],
            $rows[0]['status']
        );
        $order->setId($rows[0]['id']);

        return $order;
    }
}