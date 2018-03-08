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

    public function __construct($dsn, $user, $password, $clientName) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;

        $pdo = $this->newConnection();

        $columns = [
            '`id` serial PRIMARY KEY ',                                     // c1
            '`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',     // c2
            '`amazon_order_id` VARCHAR(255) NULL DEFAULT NULL ',            // c3
            '`merchant_order_id` VARCHAR(255) ',                            // c4
            '`purchase_date` VARCHAR(255) NULL DEFAULT NULL ',              // c5
            '`quantity` VARCHAR(255) NULL DEFAULT NULL ',                   // c6
            '`item_price` VARCHAR(255) NULL DEFAULT NULL ',                 // c7
            '`asin` VARCHAR(255) NULL DEFAULT NULL ',                       // c8
            '`product_name` VARCHAR(255) NULL DEFAULT NULL ',               // c9
            '`url` VARCHAR(255) NULL DEFAULT NULL ',                        // c10
            '`sku` VARCHAR(255) NULL DEFAULT NULL ',                        // c11

            // set the index
            'UNIQUE `AMAZON_ORDER` (`amazon_order_id`(255))',
        ];

        // set column names so other functions can access it
        $this->allTheCurRowsNames = array_map(function ($colDef) {
            return explode(" ", $colDef)[0];
        }, $columns);

        $tableName = $clientName . "_fba_sales_v1";
        echo " \n( in the AddClientModel, table name = $tableName )";
        // actually create the table
        $colText = implode(", ", $columns);
        $pdo->query("CREATE TABLE IF NOT EXISTS $tableName ($colText)");
    }

    public function createCustomFbaOrdersTable($clientName) {

    }

    public function storeReportData($reportData) {

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