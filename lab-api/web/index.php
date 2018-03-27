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
        echo "<br> ( in index.php dynamic-client-add switch case ) <br>";
        require __DIR__ . '/../src/ctrl.dynamic.client.add.php';
        break;
    case "quote":
        echo "<br> ( in index.php QUOTE switch case ) <br>";
        require __DIR__ . '/../src/ctrl.quote.php';
        break;
    case "city-bicycle-company":
        echo "<br> ( in index.php City Bicycle switch case ) <br>";
        require __DIR__ . '/../src/ctrl.reports.php';
        break;
    case "prime-time-packaging":
        echo "<br> ( in index.php Prime Time Packaging switch case ) <br>";
        require __DIR__ . '/../src/ctrl.prime.time.pack.php';
        break;
    case "majide":
        echo "<br>( in index.php MAJIDE switch case )<br>";
        require __DIR__ . '/../src/ctrl.majide.php';
        break;
    default:
        echo "<br><br> ( index.php switch default, NOT a dynamic client add or quote ) <br><br>";
}

if($clientAction) {
    require __DIR__ . '/../src/append.ctrl.php';
}
