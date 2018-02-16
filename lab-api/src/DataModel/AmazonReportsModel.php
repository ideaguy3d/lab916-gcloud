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
            'INSERT INTO cbc_fba_sales_v1 (%s) VALUES (%s)',
            implode(', ', $colNames),
            implode(', ', $placeholders)
        );
        $statement = $pdo->prepare($sql);

        // converting indexed array to an assoc array
        for ($row = 1; $row < (count($reports) - 1); $row++) {
            $col = 0;
            $curRow = $reports[$row];
            $u = null;
            // right now data is depending on index of array, but eventually there
            // should be regex patterns to ensure the index is the data we think it is
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
            $url = "amazon.com/dp/" . $asin; // index 12 should contain asin

            // this loops creates an assoc.ar and gives it a default val.
            foreach ($colNames as $name) {
                $recDataAssoc[$colNames[$col]] = $name;
                $col++;
            }

            // c1
            $recDataAssoc["amazon_order_id"] = $amazonOrderId;
            // c2
            $recDataAssoc["merchant_order_id"] = $merchantOrderId;
            // c3
            $recDataAssoc["purchase_date"] = $purchaseDate;
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