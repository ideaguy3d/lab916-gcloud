<?php

namespace Google\Cloud\Samples\Bookshelf;

$action = isset($_GET["action"]) ? $_GET["action"] : null;

$email = isset($_GET['email']) ? $_GET["email"] : null;
$clientName = isset($_GET["client-name"]) ? $_GET["client-name"] : null;
$tableName = isset($_GET["table-name"]) ? $_GET["table-name"] : null;
// $mwsAuthKey = isset($_GET["key"]) ? $_GET["key"] : null;

if($action === 'create-table') {
    $clientInfo = [
        "client_name" => $clientName,
        "client_description" => "the client description of the client",
    ];

    $model = $app["bookshelf.model"]($app);
    $model->createSimpleClientReport($clientInfo, $tableName);
}

