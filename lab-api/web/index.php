<?php

echo "<style> .lab-btn {padding: 16px; border: solid 1px cornflowerblue;}</style>";


echo "<h1>Navigation:</h1>";

echo "<a href='/info.php' class='lab-btn'>LAB 916 api info</a>";


require_once __DIR__ . '/../vendor/autoload.php';

//-- The application:
$app = require __DIR__ . '/../src/app.php';

//-- CONTROLLER =Quote Questionnaire:
require __DIR__ . '/../src/ctrl.quote.php';
//-- CONTROLLER =CBC City Bicycle Company API:
require __DIR__ . '/../src/ctrl.reports.php';
//-- CONTROLLER =PTP Prime Time Packaging API:
require __DIR__ . '/../src/ctrl.prime.time.pack.php';
//-- CONTROLLER =Dynamically Add client to get report
require  __DIR__ . '/../src/ctrl.dynamic.client.add.php';