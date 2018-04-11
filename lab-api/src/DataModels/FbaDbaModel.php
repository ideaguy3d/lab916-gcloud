<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 4/10/2018
 * Time: 4:04 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModels;

use PDO;

class FbaDbaModel implements FbaDbaInterface
{
    private $dsn;
    private $user;
    private $password;

    public function __construct($dsn, $user, $password) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
    }

    private function newConnection() {
        $pdo = new PDO($this->dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public function orderStatusAudit($tableName) {
        $pdo = $this->newConnection();

        $query = "SELECT * FROM $tableName ORDER BY amazon_order_id";
        $statement = $pdo->prepare($query);
        $statement->execute();

        $rows = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
        }

        echo "break point here";

        return 1;
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName,
                $dbName
            );
        }
        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}