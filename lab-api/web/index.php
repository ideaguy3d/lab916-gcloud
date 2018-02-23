<?php

echo "<h1> ~ The Lab916 API ~</h1>";
echo "<h2>Front ends:</h2>";
echo "<p>Quote front end: <a href='/quote.html'>Quote</a></p>";
echo "<h2>Back end are query strings from root.</h2>";
echo "<p>For example: <code>site.com/?action=...</code></p>";

require_once __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../src/app.php';

//-- CONTROLLER =Quote Questionnaire:
require __DIR__ . '/../src/ctrl.quote.php';
//-- CONTROLLER =CBC City Bicycle Company API:
require __DIR__ . '/../src/ctrl.reports.php';
//-- CONTROLLER =PTP Prime Time Packaging API:
require __DIR__ . '/../src/ctrl.prime.time.pack.php';