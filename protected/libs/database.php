<?php

include_once(dirname(__FILE__) . '/config.php');

class Database {

    /**
     * Retourne une connexion à la base de données.
     * @return resource
     */
    public static function getConnection() {

		return odbc_connect(
			'Driver={SQL SERVER}; Server=' . DB_HOST . '; Database=' . DB_NAME . ';',
			DB_USERNAME,
			DB_PASSWORD
		);
    }
}
