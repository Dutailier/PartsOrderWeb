<?php
include_once('config.php');
include_once(ROOT . 'libs/entities/retailer.php');
include_once(ROOT . 'libs/repositories/users.php');

class Retailers
{
    public static function getConnected()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION['retailer'])) {
            $user = Users::getConnected();
            $retailer = Retailers::FilterByUserId($user->getId());
            $_SESSION['retailer'] = $retailer;
        }

        return $_SESSION['retailer'];
    }

    public static function FilterByUserId($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getRetailersByUserId]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Retailer(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'user_id'),
                    odbc_result($result, 'name'),
                    odbc_result($result, 'phone'),
                    odbc_result($result, 'email'),
                    odbc_result($result, 'address_id'));
            }
        }
    }

    public static function Find($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {

            $sql = '{CALL [BruPartsOrderDb].[dbo].[getRetailer]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new Retailer(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'user_id'),
                    odbc_result($result, 'name'),
                    odbc_result($result, 'phone'),
                    odbc_result($result, 'email'),
                    odbc_result($result, 'address_id'));
            }
        }
    }
}