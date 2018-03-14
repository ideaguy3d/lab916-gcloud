<?php

/*
echo "<style> .lab-btn {padding: 16px; border: solid 1px cornflowerblue;}</style>";
echo "<h1>Navigation:</h1>";
echo "<a href='/info.php' class='lab-btn'>LAB 916 api info</a>";
*/

require_once __DIR__ . '/../vendor/autoload.php';

//-- The action:
$action = isset($_GET["action"]) ? $_GET["action"] : null;

// force action for debugging purposes:
$action = "dynamic-client-add";

//-- The application:
$app = require __DIR__ . '/../src/app.php';

//-- CONTROLLERS --\\
// figure out how to implement this using an associative array.
switch ($action) {
    case "dynamic-client-add":
        echo " \n( in index.php dynamic-client-add switch case ) ";
        require __DIR__ . '/../src/ctrl.dynamic.client.add.php';
        break;
    case "quote":
        require __DIR__ . '/../src/ctrl.quote.php';
        break;
    case "city-bicycle-company":
        require __DIR__ . '/../src/ctrl.reports.php';
        break;
    case "prime-time-packaging":
        require __DIR__ . '/../src/ctrl.prime.time.pack.php';
        break;
    case "action":
        // require __DIR__ . '';
        break;
    default:
        echo " \n( index.php switch default ) ";
}
