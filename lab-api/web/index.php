<?php

/*
echo "<style> .lab-btn {padding: 16px; border: solid 1px cornflowerblue;}</style>";
echo "<h1>Navigation:</h1>";
echo "<a href='/info.php' class='lab-btn'>LAB 916 api info</a>";
*/

require_once __DIR__ . '/../vendor/autoload.php';

//-- The action:
$action = isset($_GET["action"]) ? $_GET["action"] : null;
$clientAction = isset($_GET["client-action"]) ? $_GET["client-action"] : null;

//--------------------------------------------
// DEBUGGING - Force action for DEBUGGING APP
//--------------------------------------------
//$action = "majide";

//-- The application:
$app = require __DIR__ . '/../src/app.php';

//-- CONTROLLERS --\\
// figure out how to implement this using an associative array.
switch ($action) {
    case "dynamic-client-add":
        echo "<br><mark> index.php > 'dynamic-client-add' switch case </mark><br>";
        require __DIR__ . '/../src/ctrl.dynamic.client.add.php';
        break;
    case "quote":
        echo "<br><mark> index.php > 'quote' switch case </mark><br>";
        require __DIR__ . '/../src/ctrl.quote.php';
        break;
    case "order-status-task":
        echo "<br><mark> index.php > switch case / Doing the order status DBA task </mark><br>";
        require __DIR__ . '/../src/ctrl.order.status.task.php';
        break;
    case "city-bicycle-company":
        echo "<br><mark> index.php > City Bicycle switch case </mark><br>";
        require __DIR__ . '/../src/ctrl.reports.php';
        break;
    case "prime-time-packaging":
        echo "<br><mark> index.php Prime Time Packaging switch case </mark><br>";
        require __DIR__ . '/../src/ctrl.prime.time.pack.php';
        break;
    case "majide":
        echo "<br><mark> index.php MAJIDE switch case </mark><br>";
        require __DIR__ . '/../src/ctrl.majide.php';
        break;
    default:
        echo "<br><mark> index.php > switch default / Not a dynamic client add or quote. </mark><br>";
}

if($clientAction) {
    echo "<br><mark> index.php > if(clientAction) / There was a 'Client Action' </mark><br>";
    require __DIR__ . '/../src/append.ctrl.php';
}
else {
    echo "<br><mark> index.php > if(clientAction) / There was no 'Client Action' </mark><br>";
}
