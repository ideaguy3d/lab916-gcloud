<?php

namespace Google\Cloud\Samples\Bookshelf;

use Google\Cloud\Samples\Bookshelf\DataModel\DataModelInterface;

$action = isset($_GET["action"]) ? $_GET["action"] : null;

$email = isset($_GET['email']) ? $_GET["email"] : null;
$clientName = isset($_GET["client-name"]) ? $_GET["client-name"] : null;
$tableName = isset($_GET["table-name"]) ? $_GET["table-name"] : null;
$mwsAuthKey = isset($_GET["key"]) ? $_GET["key"] : null;

$addBook = isset($_GET['add-book']) ? $_GET["add-book"] : null;
$getBooks = isset($_GET['get-books']) ? $_GET["get-books"] : null;


if ($email) {
    echo 'Your email is = ' . $_GET['email'];
}

if ($addBook === "true") {
    /** @var DataModelInterface $model */
    $model = $app['bookshelf.model']($app);
    $model->create($book);

    echo "add book? = " . $_GET['add-book'] . "<br><br>";
}

if ($getBooks === 'true') {
    $model = $app['bookshelf.model']($app);
    $books = $model->listBooks();

    echo "get-books is true. So I should see books...";

    echo print_r($books);
}

if($action === 'create-table') {
    $clientInfo = [
        "client_name" => $clientName,
        "client_description" => "the client description of the client",
    ];

    $model = $app["bookshelf.model"]($app);
    $model->createSimpleClientReport($clientInfo, $tableName);
}

