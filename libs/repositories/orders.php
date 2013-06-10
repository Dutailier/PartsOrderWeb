<?php

include_once(ROOT . 'libs/database.php');
include_once(ROOT . 'libs/entities/order.php');

class Orders
{
    public static function Attach(Order $order)
    {
        $query = 'EXEC [addOrderByStoreId]';
        $query .= '@shippingAddressId = "' . $order->getShippingAddressId() . '", ';
        $query .= '@storeId = "' . $order->getStoreId() . '", ';
        $query .= '@receiverId = "' . $order->getReceiverId() . '"';

        $rows = Database::Execute($query);

        if (empty($rows)) {
            throw new Exception('The order wasn\'t added.');
        }

        $order->setId($rows[0]['id']);
        $order->setStatus(($rows[0]['status']));
        $order->setNumber($rows[0]['number']);
        $order->setCreationDate($rows[0]['creationDate']);
        $order->setLastModificationByUserId($rows[0]['lastModificationByUserId']);
        $order->setLastModificationDate($rows[0]['lastModificationDate']);

        return $order;
    }

    public static function ConfirmByUserId($orderId, $userId)
    {
        $query = 'EXEC [confirmOrderByUserId]';
        $query .= '@orderId = "' . intval($orderId) . '", ';
        $query .= '@userId = "' . intval($userId) . '"';

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
            $rows[0]['lastModificationByUserId'],
            $rows[0]['lastModificationDate'],
            $rows[0]['status']
        );
        $order->setId($rows[0]['id']);

        return $order;
    }

    public static function CancelByUserId($orderId, $userId)
    {
        $query = 'EXEC [cancelOrderByUserId]';
        $query .= '@orderId = "' . intval($orderId) . '", ';
        $query .= '@userId = "' . intval($userId) . '"';

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
            $rows[0]['lastModificationByUserId'],
            $rows[0]['lastModificationDate'],
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
            $rows[0]['shippingAddressId'],
            $rows[0]['storeId'],
            $rows[0]['receiverId'],
            $rows[0]['number'],
            $rows[0]['creationDate'],
            $rows[0]['lastModificationByUserId'],
            $rows[0]['lastModificationDate'],
            $rows[0]['status']
        );
        $order->setId($rows[0]['id']);

        return $order;
    }

    public static function FilterByStoreId($id)
    {
        $query = 'EXEC [getOrdersByStoreId]';
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
                $row['lastModificationByUserId'],
                $row['lastModificationDate'],
                $row['status']
            );
            $order->setId($row['id']);

            $orders[] = $order;
        }
        return $orders;
    }

    public static function Latest($numberOfOrders)
    {
        $query = 'EXEC [getLastOrders]';
        $query .= '@numberOfOrders = "' . intval($numberOfOrders) . '"';

        $rows = Database::Execute($query);

        $orders = array();
        foreach ($rows as $row) {

            $order = new Order(
                $row['shippingAddressId'],
                $row['storeId'],
                $row['receiverId'],
                $row['number'],
                $row['creationDate'],
                $row['lastModificationByUserId'],
                $row['lastModificationDate'],
                $row['status']
            );
            $order->setId($row['id']);

            $orders[] = $order;
        }
        return $orders;
    }
}