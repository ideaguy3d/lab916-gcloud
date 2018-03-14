<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/27/2018
 * Time: 3:52 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModel;

use PDO;

/**
 * Class AddClientModel
 * @package Lab916\Cloud\Amazon\Mws\Reports\DataModel
 */
class AddClientModel implements AddClientInterface
{
    private $dsn;
    private $user;
    private $password;
    private $allTheCurRowsNames;
    private $tableName;

    public function __construct($dsn, $user, $password, $clientName) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;

        $pdo = $this->newConnection();

        $columns = [
            'id serial PRIMARY KEY ',                                     // c1
            'timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',     // c2
            'amazon_order_id VARCHAR(255) NULL DEFAULT NULL ',            // c3
            'merchant_order_id VARCHAR(255) ',                            // c4
            'purchase_date VARCHAR(255) NULL DEFAULT NULL ',              // c5
            'last_updated_date VARCHAR(255) NULL DEFAULT NULL ',          // c6
            'order_status VARCHAR(255) NULL DEFAULT NULL ',               // c7
            'fulfillment_channel VARCHAR(255) NULL DEFAULT NULL ',        // c8
            'sales_channel VARCHAR(255) NULL DEFAULT NULL ',              // c9
            'url VARCHAR(255) NULL DEFAULT NULL ',                        // c10
            'ship_service_level VARCHAR(255) NULL DEFAULT NULL ',         // c11
            'product_name VARCHAR(255) NULL DEFAULT NULL ',               // c12
            'sku VARCHAR(255) NULL DEFAULT NULL ',                        // c13
            'asin VARCHAR(255) NULL DEFAULT NULL ',                       // c14
            'item_status VARCHAR(255) NULL DEFAULT NULL ',                // c15
            'quantity VARCHAR(255) NULL DEFAULT NULL ',                   // c16
            'currency VARCHAR(255) NULL DEFAULT NULL ',                   // c17
            'item_price VARCHAR(255) NULL DEFAULT NULL ',                 // c18
            'item_tax VARCHAR(255) NULL DEFAULT NULL ',
            'shipping_price VARCHAR(255) NULL DEFAULT NULL ',             // c19
            'ship_promotion_discount VARCHAR(255) NULL DEFAULT NULL ',    // c20
            'ship_city VARCHAR(255) NULL DEFAULT NULL ',                  // c19
            'ship_state VARCHAR(255) NULL DEFAULT NULL ',                 // c20
            'ship_postal_code VARCHAR(255) NULL DEFAULT NULL ',           // c21
            'ship_country VARCHAR(255) NULL DEFAULT NULL ',               // c22
            'promotion_ids VARCHAR(255) NULL DEFAULT NULL ',              // c23
            'is_business_order VARCHAR(255) NULL DEFAULT NULL ',          // c24
            'purchase_order_number VARCHAR(255) NULL DEFAULT NULL ',
            'price_designation VARCHAR(255) NULL DEFAULT NULL ',
            // set the index
            'UNIQUE `AMAZON_ORDER` (`amazon_order_id`(255))',
        ];

        // set column names so other functions can access it
        $this->allTheCurRowsNames = array_map(function ($colDef) {
            return explode(" ", $colDef)[0];
        }, $columns);
        // get rid of last elem since it's an index not a column name

        $this->tableName = $clientName . "_fba_sales_v1";
        echo " \n( in the AddClientModel.php __c, table name = $this->tableName )";
        // actually create the table
        $colText = implode(", ", $columns);
        $pdo->query("CREATE TABLE IF NOT EXISTS $this->tableName ($colText)");

        array_splice($this->allTheCurRowsNames, -1);
    }

    // Will dynamically create a report from a web form.
    public function createReport($reports) {
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
        $col = 0;
        $placeholders = array_map(function ($key) {
            return ":$key";
        }, $colNames);

        $sql = sprintf("INSERT INTO $this->tableName (%s) VALUES (%s)",
            implode(", ", $colNames),
            implode(", ", $placeholders)
        );

        $statement = $pdo->prepare($sql);

        // ----------------------------------------------------------------------
        // Insert data into table (CREATE op of CRUD), $row = 1 because index 0
        // are the column names.
        // ----------------------------------------------------------------------
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

            try {
                //-- INSERT DATA:
                $resultSet = $statement->execute($recDataAssoc);
                echo "<p>res set = $resultSet</p>";
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

        return " - LAB 916 - last inserted id = " . $pdo->lastInsertId();
    }

    private function newConnection() {
        $pdo = new PDO($this->dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf(
                'mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName,
                $dbName
            );
        }

        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}