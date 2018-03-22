<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/20/2018
 * Time: 4:53 PM
 */

namespace Lab916\Cloud\Reports\DataModel;

use PDO;

// TODO: create an interface for this data model class
class UpdateFbaReports implements UpdateFbaReportsInterface
{
    private $dsn;
    private $user;
    private $password;
    private $clientTables;

    public function __construct($dsn, $user, $password) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;
    }

    public function appendFbaReports($reports, $tableName) {
        $pdo = $this->newConnection();
        $colNames = [
            'amazon_order_id',      // c1
            'merchant_order_id',    // c2
            'purchase_date',        // c3
            'last_updated_date',    // c4
            'order_status',         // c5
            'fulfillment_channel',  // c6
            'sales_channel',        // c7
            'url',                  // c8
            'ship_service_level',   // c9
            'product_name',         // c10
            'sku',                  // c11
            'asin',                 // c12
            'item_status',          // c13
            'quantity',             // c14
            'currency',             // c15
            'item_price',           // c16
            'shipping_price',       // c17
            'ship_promotion_discount',// c18
            'ship_city',            // c19
            'ship_state',           // c20
            'ship_postal_code',     // c21
            'ship_country',         // c22
            'promotion_ids',        // c23
            'is_business_order',    // c24
        ];
        $recDataAssoc = [];
        $track = 0;
        $placeholders = array_map(function ($key) {
            return ":$key";
        }, $colNames);
        $sql = sprintf("INSERT INTO $tableName (%s) VALUES (%s)",
            implode(", ", $colNames),
            implode(", ", $colNames)
        );
        $statement = $pdo->prepare($sql);
        $col = 0;

        // ----------------------------------------------
        // Insert data into table (CREATE op of CRUD),
        // $row = 1 because index 0 are column names.
        // ----------------------------------------------
        for ($row = 0; $row < count($reports); $row++) {
            $curRow = $reports[$row];
            $u = null;

            //-- Right now data is depending on index of array, but eventually there
            // should be regex patterns to ensure the index is the data we think it is:
            $amazonOrderId = isset($curRow[0]) ? $curRow[0] : $u;
            $merchantOrderId = isset($curRow[1]) ? $curRow[1] : $u;
            $purchaseDate = isset($curRow[2]) ? $curRow[2] : $u;
            $lastUpdated = isset($curRow[3]) ? $curRow[3] : $u;
            $orderStatus = isset($curRow[4]) ? $curRow[4] : $u;
            $fulChannel = isset($curRow[5]) ? $curRow[5] : $u;
            $salesChannel = isset($curRow[6]) ? $curRow[6] : $u;
            $shipServiceLabel = isset($curRow[9]) ? $curRow[9] : $u;
            $productName = isset($curRow[10]) ? $curRow[10] : $u;
            $sku = isset($curRow[11]) ? $curRow[11] : $u;
            $asin = isset($curRow[12]) ? $curRow[12] : $u;
            $itemStatus = isset($curRow[13]) ? $curRow[13] : $u;
            $quantity = isset($curRow[14]) ? $curRow[14] : $u;
            $currency = isset($curRow[15]) ? $curRow[15] : $u;
            $price = isset($curRow[16]) ? $curRow[16] : $u;
            $shipPrice = isset($curRow[18]) ? $curRow[18] : $u;
            $shipPromoDisc = isset($curRow[23]) ? $curRow[23] : $u;
            $shipCity = isset($curRow[24]) ? $curRow[24] : $u;
            $shipState = isset($curRow[25]) ? $curRow[25] : $u;
            $shipPostalCode = isset($curRow[26]) ? $curRow[26] : $u;
            $shipCountry = isset($curRow[27]) ? $curRow[27] : $u;
            $promoId = isset($curRow[28]) ? $curRow[28] : $u;
            $isBusOrder = isset($curRow[29]) ? $curRow[29] : $u;
            $url = "amazon.com/dp/" . $asin; // index 12 should contain asin, c30

            // c1
            $recDataAssoc["amazon_order_id"] = $amazonOrderId;
            // c2
            $recDataAssoc["merchant_order_id"] = $merchantOrderId;
            // c3
            $tempPurDate = preg_replace("/T.*/", "", $purchaseDate);
            $recDataAssoc["purchase_date"] = preg_replace("/-/", "", $tempPurDate);
            // c4
            $recDataAssoc["last_updated_date"] = $lastUpdated;
            // c5
            $recDataAssoc["order_status"] = $orderStatus;
            // c6
            $recDataAssoc["fulfillment_channel"] = $fulChannel;
            // c7
            $recDataAssoc["sales_channel"] = $salesChannel;
            // c8
            $recDataAssoc["url"] = $url;
            // c9
            $recDataAssoc["ship_service_level"] = $shipServiceLabel;
            // c10
            $recDataAssoc["product_name"] = $productName;
            // c11
            $recDataAssoc["sku"] = $sku;
            // c12
            $recDataAssoc["asin"] = $asin;
            // c13
            $recDataAssoc["item_status"] = $itemStatus;
            // c14
            $recDataAssoc["quantity"] = $quantity;
            // c15
            $recDataAssoc["currency"] = $currency;
            // c16
            $recDataAssoc["item_price"] = $price;
            // c17
            $recDataAssoc["shipping_price"] = $shipPrice;
            // c18
            $recDataAssoc["ship_promotion_discount"] = $shipPromoDisc;
            // c19
            $recDataAssoc["ship_city"] = $shipCity;
            // c20
            $recDataAssoc["ship_state"] = $shipState;
            // c21
            $recDataAssoc["ship_postal_code"] = $shipPostalCode;
            // c22
            $recDataAssoc["ship_country"] = $shipCountry;
            // c23
            $recDataAssoc["promotion_ids"] = $promoId;
            // c24
            $recDataAssoc["is_business_order"] = $isBusOrder;

            /**
             * Refactor this try-catch to something else so that
             * script doesn't stop once the catch block is hit.
            **/
            try {
                //-- INSERT DATA:
                $statement->execute($recDataAssoc);
                echo "<br>LAB 916 - <h1>Successfully inserted data</h1> <br>";
            }
            catch (\PDOException $e) {
                $errorMessage = $e->getMessage();
                if(strpos($errorMessage, "Duplicate") !== false and $track<1) {
                    echo "<br> - LAB916 - Error:<br>";
                    $track++;
                    echo "<strong>There was duplicate data</strong>";
                } else {
                    if($track<1) {
                        echo "<br> - LAB916 - Error:<br>";
                        $track++;
                        echo $errorMessage;
                    }
                }
                echo "<br>";
            }

            // print_r($recDataAssoc);
            $recDataAssoc = [];
        }
    }


    // TODO: implement a 'limit' and 'cursor' like example app did
    public function listClientInfo() {
        $pdo = $this->newConnection();
        $query = 'SELECT * FROM client_info ORDER BY id';
        $statement = $pdo->prepare($query);
        $statement->execute();

        $rows = [];
        $last_row = null;
        $new_cursor = null;

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            array_push($rows, $row);
            $last_row = $row;
        }

        return [
            'clients' => $rows,
            'lastRow' => $last_row
        ];
    }

    private function newConnection() {
        $pdo = new PDO($this->dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }

        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}