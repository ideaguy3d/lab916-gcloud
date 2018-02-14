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
        $colNames = [
            'amazon_order_id',      // c1
            'merchant_order_id',    // c2
            'purchase_date',        // c3
            'last_updated_date',    // c4
            'order_status',          // c5
            'fulfillment_channel',  // c6
            'sales_channel',        // c7
            'order_channel',        // c8
            'url',                  // c9
            'ship_service_level',   // c10
            'product_name',         // c11
            'sku',                  // c12
            'asin',                 // c13
            'item_status',          // c14
            'quantity',             // c15
            'currency',             // c16
            'item_price',           // c17
            'item_tax',             // c18
        ];
        $placeholders = array_map(function ($key) {
            return ":$key";
        }, $colNames);

        // iterate over a 2-dimensional array
        $recData = [];        // don't get last row
        for ($row = 0; $row < (count($reports) - 1); $row++) {
            $col = 0;
            foreach ($reports[$row] as $report) {
                if (strpos($recData[$col], ':') !== false) {// ':' was found in this cell
                    $recData[$col] = str_replace(':', '-', $recData[$col]);
                } else {
                    $recData[$col] = $report;
                }
                $col++;
            }
        }
        
        $recDataAssoc = []; $idx = 0;
        foreach ($reports[2] as $report) {
            $recDataAssoc[$colNames[$idx]] = $report;
            $idx++;
        }

        $sql = sprintf(
            'INSERT INTO fba_one (%s) VALUES (%s)',
            implode(', ', $colNames),
            implode(', ', $placeholders)
        );

        $statement = $pdo->prepare($sql);
        $statement->execute($recDataAssoc);

        return $pdo->lastInsertId();
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }
        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}