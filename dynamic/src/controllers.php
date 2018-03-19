<?php

namespace Google\Cloud\Samples\Bookshelf;


$model = $app["bookshelf.model"]($app);

$result = $model->spartanTask();

echo "<br>Last inserted ID = $result <br>";