<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/20/2018
 * Time: 4:53 PM
 */

namespace Lab916\Cloud\Reports\Fba;

use PDO;

// TODO: create an interface for this data model class
class UpdateFbaReports
{
    private $clientTables;

    public function __construct($dsn, $user, $password) {

    }

    public function appendFbaReports() {
        
    }
    
    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }

        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}