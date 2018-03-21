<?php

namespace Google\Cloud\Samples\Bookshelf;

$modelFbaReport = $app["fba.reports.model"]($app);

$result = $model->updateFbaReports();

echo "<br>Last inserted ID = $result <br>";