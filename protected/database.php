<?php

require_once 'config.php';

class Database {

    public static function getConnection() {

		return odbc_connect(
			'Driver={SQL SERVER}; Server=' . DB_HOST . '; Database=' . DB_NAME . ';',
			DB_USERNAME,
			DB_PASSWORD
		);
    }
}
