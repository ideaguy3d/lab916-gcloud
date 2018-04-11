<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 4/10/2018
 * Time: 3:31 PM
 */

$model = $app['order-status-task.model']($app);

echo '<hr><br> ctrl.order.status.task.php / Getting file contents from our own app on gCloud.<br>';

// this is how I will try to offload workload to other scripts, then I'll use
// timing or stopwatch like functions to see how long this takes.
$zContents = file_get_contents("https://labdata-916.appspot.com");

//TODO: make a function similar to 'getAmwsCredentials()' in the AmazonReportsModel.php for FbaDbaModel.php
// some model function to get table names for each client

// for now manually pass in table name:
$tableName = 'ptp_fba_sales_v1';
$model->orderStatusAudit($tableName);

echo '<br>ctrl.order.status.task.php / The contents are: <br><br>';
echo "<div> $zContents </div><hr>";