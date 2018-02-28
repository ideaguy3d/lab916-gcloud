<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/27/2018
 * Time: 3:52 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModel;

use PDO;

class AddClientModel implements AddClientInterface
{
    private $dsn;
    private $user;
    private $password;
    private $allTheCurRows;

    public function __construct($dsn, $user, $password) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;

        $columns = [
            'id serial PRIMARY KEY ',
            'amazon_order_id VARCHAR(255)',
            'quantity VARCHAR(255)',
            'purchase_date VARCHAR(255)',
            'asin VARCHAR(255)',
            'product_name VARCHAR(255)',
            'url VARCHAR(255)',
            'sku VARCHAR(255)',
            'item_price VARCHAR(255)'
        ];

        // set column names so other functions can access it
        $this->colNames = array_map(function ($colDef) {
            return explode(" ", $colDef)[0];
        }, $columns);

        $colText = implode(", ", $columns);
        $pdo = $this->newConnection();
        // "custom_report" table will have to become a variable
        $pdo->query("CREATE TABLE IF NOT EXISTS custom_report ($colText)");
    }

    public function storeReportData($reportData) {

    }

    private function newConnection() {
        $pdo = new PDO($this->dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}