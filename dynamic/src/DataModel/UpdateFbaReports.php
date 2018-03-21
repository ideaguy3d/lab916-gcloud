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
    }


    // TODO: implement a 'limit' and 'cursor' like example app did
    public function listClientInfo() {
        $pdo = $this->newConnection();
        $query = 'SELECT * FROM client_info ORDER BY id';
        $statement = $pdo->prepare($query);
        $statement->execute();

        $rows =[];
        $last_row = null;
        $new_cursor = null;

        while($row = $statement->fetch(PDO::FETCH_ASSOC)) {
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
        if($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }

        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}