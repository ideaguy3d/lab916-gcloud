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
            'order_status',         // c5
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
            'shipping_price',       // c19
            'shipping_tax',         // c20
            'ship_city',            // c21
            'ship_state',           // c22
            'ship_postal_code',     // c23
            'ship_country',         // c24
            'purchase_order_number',// c25
        ];
        $placeholders = array_map(function ($key) {
            return ":$key";
        }, $colNames);
        $recDataAssoc = [];

        /* // iterate over a 2-dimensional array to replace ':' with '-'
        $recData = [];
        for ($row = 0; $row < (count($reports) - 1); $row++) {
            $col = 0;
            foreach ($reports[$row] as $report) {
                if (@strpos($recData[$col], ':') !== false) {// ':' was found in this cell
                    $recData[$col] = str_replace(':', '-', $recData[$col]);
                } else {
                    $recData[$col] = $report;
                }
                $col++;
            }
        }
        */

        $sql = sprintf(
            'INSERT INTO fba_one (%s) VALUES (%s)',
            implode(', ', $colNames),
            implode(', ', $placeholders)
        );
        $statement = $pdo->prepare($sql);

        // converting indexed array to an assoc array
        for ($row = 1; $row < (count($reports) - 1); $row++) {
            $col = 0;
            $curRow = $reports[$row];
            $u = "unknown";
            $amazonOrderId = isset($curRow[0]) ? $curRow[0] : $u;
            $merchantOrderId = isset($curRow[1]) ? $curRow[1] : $u;
            $purchaseDate = isset($curRow[2]) ? $curRow[2] : $u;
            $lastUpdated = isset($curRow[3]) ? $curRow[3] : $u;
            $sku = isset($curRow[11]) ? $curRow[11] : $u;
            $asin = isset($curRow[12]) ? $curRow[12] : $u;
            $quantity = isset($curRow[14]) ? $curRow[14] : $u;
            $currency = isset($curRow[15]) ? $curRow[15] : $u;
            $price = isset($curRow[16]) ? $curRow[16] : $u;

            $url = "amazon.com/foo/" . $asin; // index 12 should contain asin

            // this loops creates an assoc.ar and gives it a default val.
            foreach ($colNames as $name) {
                $recDataAssoc[$colNames[$col]] = $name;
                $col++;
            }

            $recDataAssoc["amazon_order_id"] = $amazonOrderId;
            $recDataAssoc["merchant_order_id"] = $merchantOrderId;
            $recDataAssoc["url"] = $url;
            $recDataAssoc["sku"] = $sku;
            $recDataAssoc["asin"] = $asin;
            $recDataAssoc["quantity"] = $quantity;
            $recDataAssoc["currency"] = $currency;
            $recDataAssoc["item_price"] = $price;

            echo "<br> - record data assoc:<br>"; print_r($recDataAssoc); echo "<br><br>";
            echo " Size = " . count ($recDataAssoc);

            $statement->execute($recDataAssoc);
            $recDataAssoc = [];
        }

        return $pdo->lastInsertId();
    }

    private function columnCorrectAlign($data) {
        foreach ($data as $index => $datum) {
            // build something
        }
        foreach ($data as $datum) {
            // build something else
        }
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }
        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}