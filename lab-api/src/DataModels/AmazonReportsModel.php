<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/13/2018
 * Time: 3:50 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModels;

use PDO;

class AmazonReportsModel implements AmazonReportsInterface
{
    private $dsn;
    private $user;
    private $password;
    private $allTheCurRows;

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

    // hardcoded in the mws script
    public function createCbcGetReport($reports, $id = null) {
        $pdo = $this->newConnection();
        // colNames so I know which cols to insert data into.
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
        $track = 0; // so error is echoed only once.
        $col = 0;

        $sql = sprintf(
            'INSERT INTO cbc_fba_sales_v1 (%s) VALUES (%s)',
            implode(', ', $colNames),
            implode(', ', $placeholders)
        );
        $statement = $pdo->prepare($sql);

        // this loops creates an assoc.ar and gives it a default val.
        // I'm pretty sure this loop is pointless, will double check then delete.
        foreach ($colNames as $name) {
            $recDataAssoc[$colNames[$col]] = $name;
            $col++;
        }

        // ----------------------------------------------------------------------
        // Converting indexed array to an assoc.ar THEN inserting data into table
        for ($row = 1; $row < (count($reports) - 1); $row++) {
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
            $url = "amazon.com/dp/" . $asin; // index 12 should contain asin

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

            try {
                //-- INSERT DATA:
                $statement->execute($recDataAssoc);
            }
            catch (\PDOException $e) {
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, "Duplicate") !== false and $track < 1) {
                    echo "<br> - LAB916 - Error:<br>";
                    $track++;
                    echo "<strong>There was duplicate data</strong>";
                } else {
                    if ($track < 1) {
                        echo "<br> - LAB916 - Error:<br>";
                        $track++;
                        echo $errorMessage;
                    }
                }
                echo "<br>";
            }

            $recDataAssoc = [];
        }

        return " - LAB 916 - last inserted id = " . $pdo->lastInsertId();
    }

    // hardcoded in the mws script
    public function createPtpGetReport($reports) {
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
        $track = 0; // so error is echoed only once.
        $col = 0;

        $sql = sprintf(
            'INSERT INTO ptp_fba_sales_v1 (%s) VALUES (%s)',
            implode(', ', $colNames),
            implode(', ', $placeholders)
        );
        $statement = $pdo->prepare($sql);

        //-- Loop creates an assoc.ar and gives it a default val:
        foreach ($colNames as $name) {
            $recDataAssoc[$colNames[$col]] = $name;
            $col++;
        }

        // ------------------------------------------------------------------------
        // Converting indexed array to an assoc.ar THEN inserting data into table
        // ------------------------------------------------------------------------
        for ($row = 1; $row < (count($reports) - 1); $row++) {
            $curRow = $reports[$row];
            $u = null; // unknown
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
            $url = "amazon.com/dp/" . $asin; // index 12 should contain asin

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

            try {
                //-- INSERT DATA:
                $statement->execute($recDataAssoc);
            }
            catch (\PDOException $e) {
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, "Duplicate") !== false and $track < 1) {
                    echo "<br> - LAB916 - Error:<br>";
                    $track++;
                    echo "<strong>There was duplicate data</strong>";
                } else {
                    if ($track < 1) {
                        echo "<br> - LAB916 - Error:<br>";
                        $track++;
                        echo $errorMessage;
                    }
                }
                echo "<br>";
            }

            $recDataAssoc = [];
        }

        return " - LAB 916 - last inserted id = " . $pdo->lastInsertId();
    }

    // dynamic created from url q string
    public function createReport($reports, $tableName) {
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
        $track = 0; // so error is echoed only once.

        $sql = sprintf(
            'INSERT INTO ' . $tableName . ' (%s) VALUES (%s)',
            implode(', ', $colNames),
            implode(', ', $placeholders)
        );
        $statement = $pdo->prepare($sql);

        /*
        ------------------------------------------------------------------------
        Converting indexed array to an assoc.ar THEN inserting data into table
        Starting at $row=1 because $row=0 would be column titles
        ------------------------------------------------------------------------
        */
        for ($row = 1; $row < (count($reports) - 1); $row++) {
            $curRow = $reports[$row];
            $u = null; // unknown
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
            $url = "amazon.com/dp/" . $asin; // index 12 should contain asin

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

            try {
                //-- INSERT DATA:
                $statement->execute($recDataAssoc);
                echo "<br><br> LAB 916, successfully inserted <strong>data into $tableName</strong>, AmazonReportsModel.php -> createReport() <br>";
            }
            catch (\PDOException $e) {
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, "Duplicate") !== false && $track < 1) {
                    echo "<br><br> - LAB 916 - Error AmazonReportsModel.php createMajideReport():<br>";
                    $track++;
                    echo "<strong>There was duplicate data</strong>";
                } else {
                    echo "<br><br> - LAB916 - Error AmazonReportsModel.php createMajideReport() line 489 ish:<br>";
                    $track++;
                    echo $errorMessage;
                }
                echo "<br>";
            }

            $recDataAssoc = [];
        }
    }

    public function getAmwsCredentials($clientAction) {
        $pdo = $this->newConnection();
        $query = 'SELECT * FROM client_info WHERE client_action = :clientAction';
        $statement = $pdo->prepare($query);
        $statement->bindValue('clientAction', $clientAction);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function listFbaRows($limit = 10000, $cursor = null) {
        $pdo = $this->newConnection();
        if ($cursor) {
            $query = 'SELECT * FROM `cbc_fba_sales_v1` WHERE `id` > :cursor LIMIT :limit';
            $statement = $pdo->prepare($query);
            $statement->bindValue(":cursor", $cursor, PDO::PARAM_INT);
        } else {
            $query = 'SELECT * FROM `cbc_fba_sales_v1` LIMIT :limit';
            $statement = $pdo->prepare($query);
        }

        $statement->bindValue(":limit", $limit + 1, PDO::PARAM_INT);
        $statement->execute();

        $rows = [];
        $last_row = null;
        $new_cursor = null;

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (count($rows) === $limit) {
                $new_cursor = $last_row['id'];
                break;
            }
            array_push($rows, $row);
            $last_row = $row;
        }

        return ["fbaRows" => $rows, "cursor" => $new_cursor];
    }

    private function duplicateCheck($aoid, $moid) {
        // aoid = Amazon Order ID, moid = Merchant Order ID
        $fbaRowsAll = $this->allTheCurRows;
        $dupes = false; // true means there are duplicates

        for ($row = 0; count($fbaRowsAll["fbaRows"]); $row++) {
            $curRow = isset($fbaRowsAll["fbaRows"][$row]) ? $fbaRowsAll["fbaRows"][$row] : null;
            if ($curRow) {
                if ($curRow["amazon_order_id"] === $aoid) {
                    $dupes = true;
                    return $dupes;
                }
                if ($curRow["merchant_order_id"] === $moid) {
                    $dupes = true;
                    return $dupes;
                }
            } else {
                $dupes = false;
                return $dupes;
            }
        }

        return $dupes;
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }
        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}