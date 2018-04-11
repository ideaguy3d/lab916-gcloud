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
    // utility count for tracking nested functions
    private  $count;
    // will be used to get distinct `amazon_order_id` rows
    private $distinctTableRows;


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

        // get ALL of the rows in the db
        $tableRows = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($tableRows, $row);
        }

        $queryDistinct = "SELECT DISTINCT amazon_order_id from $tableName";
        $statementDistinct = $pdo->prepare($queryDistinct);
        $statementDistinct->execute();

        // get distinct amazon_order_id rows
        $this->distinctTableRows = [];
        while($row = $statementDistinct->fetch(PDO::FETCH_ASSOC)) {
            array_push($distinctTableRows, $row);
        }

        $this->count = 0;
        $groupedAmazonOrders = array_filter($tableRows, function ($val, $key) {
            echo $val == $this->distinctTableRows[$this->count]['amazon_order_id'];
            $this->count++;
        }, ARRAY_FILTER_USE_BOTH);

        echo "break point here, $groupedAmazonOrders";

        return 1;
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName,
                $dbName
            );
        }
        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}