<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/8/2018
 * Time: 2:25 PM
 */

namespace Lab916\Cloud\Quote\DataModel;

use PDO;

class SqlQuoteLab916 implements DataModelInterfaceLab916
{
    private $dsn;
    private $user;
    private $password;
    private $columnNames;

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

    public function listQuotes($limit = 30, $cursor = null) {
        $pdo = $this->newConnection();
        if ($cursor) {
            $query = 'SELECT * FROM quote WHERE id > :cursor ORDER BY id LIMIT :limit';
            $statement = $pdo->prepare($query);
            $statement->bindValue(':cursor', $cursor, PDO::PARAM_INT);
        } else {
            $query = 'SELECT * FROM quote WHERE id LIMIT :limit';
            $statement = $pdo->prepare($query);
        }

        $statement->bindValue(':limit', $limit + 1, PDO::PARAM_INT);
        $statement->execute();

        $rows = array();
        $last_row = null;
        $new_cursor = null;

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (count($rows) == $limit) {
                $new_cursor = $last_row['id'];
                break;
            }
            array_push($rows, $row);
            $last_row = $row;
        }

        return array(
            'quotes' => $rows,
            'cursor' => $new_cursor,
        );
    }

    public function create($quote, $id = null) {
        // implement a function to validate it's a quote e.g. $this->verityQuote($quote)

        if ($id) $quote['id'] = $id;
        $pdo = $this->newConnection();

        $names = array_keys($quote);
        $placeHolders = array_map(function ($key) {
            return ":$key";
        }, $names);
        print_r("place holders array =<br>" . $placeHolders);
        $sql = sprintf(
            'INSERT INTO quote (%s) VALUES (%s)',
            implode(', ', $names),
            implode(', ', $placeHolders)
        );

        $statement = $pdo->prepare($sql);
        $statement->execute($quote);
    }

    public function read($id) {
        $pdo = $this->newConnection();
        $statement = $pdo->prepare('SELECT * FROM quote WHERE id= :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update($quote, $id = null) {
        // implement a function to validate it's a quote e.g. $this->verityQuote($quote)
        $pdo = $this->newConnection();
        $assignments =
    }

    public function delete($id) {

    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName, $dbName);
        }
        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}