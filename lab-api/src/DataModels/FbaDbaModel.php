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
    private $allTableRows;
    // REALLY important column titles
    private $amznOrderId = 'amazon_order_id';
    private $amznOrderStatus = 'order_status';
    private $amznAsin = 'asin';


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

        $query = "SELECT * FROM $tableName";
        $statement = $pdo->prepare($query);
        $statement->execute();

        // get ALL of the rows in the db
        $this->allTableRows = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->allTableRows, $row);
        }

        $queryDistinct = "SELECT DISTINCT amazon_order_id from $tableName";
        $statementDistinct = $pdo->prepare($queryDistinct);
        $statementDistinct->execute();

        // get distinct amazon_order_id rows
        $this->distinctTableRows = [];
        while($row = $statementDistinct->fetch(PDO::FETCH_ASSOC)) {
            array_push($this->distinctTableRows, $row);
        }

        $this->count = 1;

        for($i=1; $i < count($this->distinctTableRows); $i++) {
            $curGroup = [];
            $val = $this->distinctTableRows[$i][$this->amznOrderId];
            if($val !== null) {
                for($row=0; $row < count($this->allTableRows); $row++) {
                    $curRow = $this->allTableRows[$row];
                    $match = $curRow[$this->amznOrderId] === $val;
                    if($match && ($val !== null)) {
                        array_push($curGroup, $curRow);
                    }
                }
            }
            echo "breakpoint";
        }

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