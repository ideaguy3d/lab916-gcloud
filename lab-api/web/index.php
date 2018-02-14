<?php

echo "<h1> ~ The Lab916 API ~</h1>";
echo "<h2>Front ends:</h2>";
echo "<p>Quote front end: <a href='/quote.html'>Quote</a></p>";
echo "<h2>Back end are query strings from root <i>e.g. /?action=...</i></h2>";

require_once __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../src/app.php';

//-- The Lab916 Quote Questionnaire ctrl:
require __DIR__ . '/../src/ctrl.quote.php';
//-- The Amazon MWS Reports API ctrl:
require __DIR__ . '/../src/ctrl.reports.php';