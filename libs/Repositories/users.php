<?php
include_once('config.php');
include_once(ROOT . 'libs/entities/user.php');

class Users
{
    public static function getConnected()
    {
        if (session_id() == '') {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            throw new Exception('You must be authenticated.');
        }

        return $_SESSION['user'];
    }

    public static function Find($id)
    {
        // Récupère la connexion à la base de données.
        $conn = Database::getConnection();

        if (empty($conn)) {
            throw new Exception('The connection to the database failed.');
        } else {
            $sql = '{CALL [BruPartsOrderDb].[dbo].[getUser]("' . $id . '")}';

            $result = odbc_exec($conn, $sql);

            if (empty($result)) {
                throw new Exception('The execution of the query failed.');
            } else {

                odbc_fetch_row($result);
                return new User(
                    odbc_result($result, 'id'),
                    odbc_result($result, 'username'));
            }
        }
    }
}