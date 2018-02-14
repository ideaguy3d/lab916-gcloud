<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/13/2018
 * Time: 3:50 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModel;

use PDO;

class AmazonReportsModel implements AmazonReportsInterface
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

    public function createGetReport($reports, $id = null) {
        $pdo = $this->newConnection();
        $colNames = ['amazon_order_id', 'merchant_order_id', 'purchase_date', 'last_updated_date', 'order_staus', 'fulfillment_channel',
            'sales_channel'];
        $recData = [];
        for($row = 0; $row < count($reports); $row++) {
            $col = 0;
            foreach ($reports[$row] as $report) {
                $recData[$col] = $report;
                $col++;
            }
        }

        $sql = sprintf(
            'INSERT INTO fba_one (%s) VALUES (%s)',
            implode(', ', $colNames),
            implode(', ', $recData[1])
        );

        $statement = $pdo->prepare($sql);
        $statement->execute($reports[1]);

        return $pdo->lastInsertId();
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }
        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}