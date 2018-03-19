<?php
/*
 * Copyright 2015 Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Google\Cloud\Samples\Bookshelf\DataModel;

use PDO;

/**
 * Class Sql implements the DataModelInterface with a mysql or postgres database.
 **/
class Sql implements DataModelInterface
{
    private $dsn;
    private $user;
    private $password;
    private $columnNames; // for the books table
    private $tasksColumnNamesAR; // for the tasks table
    private $simpleClientColumnNamesAR; // for simple client table

    /**
     * Creates the SQL books table if it doesn't already exist.
     *
     * @param mixed $dsn
     * @param mixed $user
     * @param mixed $password
    **/
    public function __construct($dsn, $user, $password) {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->password = $password;

        //$pdo = $this->newConnection();

        //$this->createBooksTable($pdo);
        //$this->createTasksTable($pdo);
    }

    private function createBooksTable($pdo) {
        $columns = array(
            'id serial PRIMARY KEY ',
            'title VARCHAR(255)',
            'author VARCHAR(255)',
            'published_date VARCHAR(255)',
            'image_url VARCHAR(255)',
            'description VARCHAR(255)',
            'created_by VARCHAR(255)',
            'created_by_id VARCHAR(255)',
        );

        // store columnNames so that it can be accessed in other functions for this model
        $this->columnNames = array_map(function ($columnDefinition) {
            return explode(' ', $columnDefinition)[0];
        }, $columns);

        // actually create the table
        $columnText = implode(', ', $columns);
        $pdo->query("CREATE TABLE IF NOT EXISTS books ($columnText)");
    }

    private function createTasksTable($pdo) {
        $columns = [
            'id serial PRIMARY KEY ',
            'task_one VARCHAR(255)',
            'task_two VARCHAR(255)',
            'title VARCHAR(255)',
            'name VARCHAR(255)',
            'deadline_date VARCHAR(255)',
            'task_image_url VARCHAR(255)',
            'description VARCHAR(255)',
        ];

        // store tasks table column names so it can be accessed by other functions
        $this->tasksColumnNamesAR = array_map(function($columnDefinition) {
            return explode(" ", $columnDefinition)[0];
        }, $columns);

        // now actually create the table
        $columnValues = implode(", ", $columns);
        $pdo->query("CREATE TABLE IF NOT EXISTS tasks ($columnValues)");
    }

    private function createSimpleClientTable($pdo, $tableName) {
        $columns = [
            'id serial PRIMARY KEY ',
            'amazon_order_id VARCHAR(255)',
            'client_name VARCHAR(255)',
            'client_description VARCHAR(255)',
        ];

        // store column names so they can be used else where in the model
        $this->simpleClientColumnNamesAR = array_map(function($columnDefinition) {
            return explode(" ", $columnDefinition)[0];
        }, $columns);

        $columnValues = implode(", ", $columns);
        $pdo->query("CREATE TABLE IF NOT EXISTS $tableName ($columnValues)");
    }

    public function createSimpleClientReport($clientInfo, $tableName) {
        $pdo = $this->newConnection();

        // create the table
        $this->createSimpleClientTable($pdo, $tableName);

        $names = array_keys($clientInfo);
        $placeholders = array_map(function($key) {
            return ":$key";
        }, $names);
        $sql = sprintf("INSERT INTO $tableName (%s) VALUES (%s)",
            implode(", ", $names), implode(", ", $placeholders));
        $statement = $pdo->prepare($sql);
        $statement->execute($clientInfo);

        return $pdo->lastInsertId();
    }

    /**
     * Creates a new PDO instance and sets error mode to exception.
     *
     * @return PDO
     */
    private function newConnection() {
        $pdo = new PDO($this->dsn, $this->user, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    /**
     * Throws an exception if $book contains an invalid key.
     *
     * @param $book array
     *
     * @throws \Exception
     */
    private function verifyBook($book) {
        if ($invalid = array_diff_key($book, array_flip($this->columnNames))) {
            throw new \Exception(sprintf(
                'unsupported book properties: "%s"',
                implode(', ', $invalid)
            ));
        }
    }

    public function listBooks($limit = 10, $cursor = null) {
        $pdo = $this->newConnection();
        if ($cursor) {
            $query = 'SELECT * FROM books WHERE id > :cursor ORDER BY id' .
                ' LIMIT :limit';
            $statement = $pdo->prepare($query);
            $statement->bindValue(':cursor', $cursor, PDO::PARAM_INT);
        } else {
            $query = 'SELECT * FROM books ORDER BY id LIMIT :limit';
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
            'books' => $rows,
            'cursor' => $new_cursor,
        );
    }

    public function create($book, $id = null) {
        $this->verifyBook($book);
        if ($id) {
            $book['id'] = $id;
        }
        $pdo = $this->newConnection();

        $names = array_keys($book);
        $placeHolders = array_map(function ($key) {
            return ":$key";
        }, $names);
        $sql = sprintf(
            'INSERT INTO books (%s) VALUES (%s)',
            implode(', ', $names),
            implode(', ', $placeHolders)
        );
        $statement = $pdo->prepare($sql);
        $statement->execute($book);

        return $pdo->lastInsertId();
    }

    public function read($id) {
        $pdo = $this->newConnection();
        $statement = $pdo->prepare('SELECT * FROM books WHERE id = :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update($book) {
        $this->verifyBook($book);
        $pdo = $this->newConnection();
        $assignments = array_map(
            function ($column) {
                return "$column=:$column";
            },
            $this->columnNames
        );
        $assignmentString = implode(',', $assignments);
        $sql = "UPDATE books SET $assignmentString WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $values = array_merge(
            array_fill_keys($this->columnNames, null),
            $book
        );
        return $statement->execute($values);
    }

    public function delete($id) {
        $pdo = $this->newConnection();
        $statement = $pdo->prepare('DELETE FROM books WHERE id = :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount();
    }

    public static function getMysqlDsn($dbName, $port, $connectionName = null) {
        if ($connectionName) {
            return sprintf('mysql:unix_socket=/cloudsql/%s;dbname=%s',
                $connectionName,
                $dbName);
        }

        return sprintf('mysql:host=127.0.0.1;port=%s;dbname=%s', $port, $dbName);
    }
}