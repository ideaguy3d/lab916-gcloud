<?php

use Lab916\Cloud\Quote\DataModel\LabQuoteModel;
use Lab916\Cloud\Amazon\Mws\Reports\DataModels\AmazonReportsModel;
use Lab916\Cloud\Amazon\Mws\Reports\DataModels\AddClientModel;
use Lab916\Cloud\Amazon\Mws\Reports\DataModels\FbaDbaModel;
use Symfony\Component\Yaml\Yaml;

$app = [];

$config = __DIR__ . '/../config/' . 'settings.yml';

$app['config'] = Yaml::parse(file_get_contents($config));

// Figure out how to implement this using an associative array !!!!!
switch ($action) { // action gets set in index.php
    // uses "AddClientModel"
    case "dynamic-client-add":
        echo "<br><mark> app.php > switch case / 'dynamic-client-add' </mark><br>";
        // Dynamically adding client reports via a form
        $app["dynamic-client-add.model"] = function ($app, $clientName) {
            $config = $app['config'];

            if (empty($config['lab916_backend'])) {
                throw new \DomainException('lab916_backend must be configured');
            }

            $mysql_dsn = AddClientModel::getMysqlDsn(
                $config['cloudsql_database_name'],
                $config['cloudsql_port'],
                getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
            );

            return new AddClientModel(
                $mysql_dsn,
                $config['cloudsql_user'],
                $config['cloudsql_password'],
                $clientName
            );
        };
        break;
    // uses "LabQuoteModel"
    case "quote":
        // The data model for the interactive quote questionnaire I built.
        $app['quote.model'] = function ($app) {
            $config = $app['config'];
            if (empty($config['quote_backend'])) {
                throw new \DomainException('quote_backend must be configured');
            }

            $mysql_dsn = LabQuoteModel::getMysqlDsn($config['cloudsql_database_name'], $config['cloudsql_port'], getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null);

            return new LabQuoteModel($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        };
        break;
    case "order-status-task":
        echo "<br><mark> app.php > switch case / Doing 'order-status=task' dbAdmin task. </mark><br>";
        $app['order-status-task.model'] = function ($app) {
            $config = $app['config'];
            // breaks DRY principles >:\
            if (empty($config['lab916_backend'])) {
                throw new \DomainException('lab916_backend must be configured');
            }

            $dsn = FbaDbaModel::getMysqlDsn(
                $config['cloudsql_database_name'],
                $config['cloudsql_port'],
                getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
            );

            return new FbaDbaModel($dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        };
        break;
    // uses "AmazonReportsModel"
    case "city-bicycle-company":
        // The Amazon MWS Report API
        $app["cbc-report.model"] = function ($app) {
            $config = $app['config'];

            if (empty($config['lab916_backend'])) {
                throw new \DomainException('lab916_backend must be configured');
            }

            $mysql_dsn = AmazonReportsModel::getMysqlDsn($config['cloudsql_database_name'], $config['cloudsql_port'], getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null);

            return new AmazonReportsModel($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        };
        break;
    // uses "AmazonReportsModel"
    case "prime-time-packaging":
        // The Amazon MWS Report API
        $app["ptp-report.model"] = function ($app) {
            $config = $app['config'];

            if (empty($config['lab916_backend'])) {
                throw new \DomainException('lab916_backend must be configured');
            }

            $mysql_dsn = AmazonReportsModel::getMysqlDsn(
                $config['cloudsql_database_name'],
                $config['cloudsql_port'],
                getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
            );

            return new AmazonReportsModel($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        };
        break;
    // uses "AmazonReportsModel"
    case "majide":
        echo "<br>( in app.php 'majide' switch case )<br>";
        $app["majide-report.model"] = function ($app) {
            $config = $app['config'];
            if (empty($config['lab916_backend'])) {
                throw new \DomainException("lab916_backend must be configured");
            }

            $mysql_dsn = AmazonReportsModel::getMysqlDsn(
                $config['cloudsql_database_name'],
                $config['cloudsql_port'],
                getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
            );

            return new AmazonReportsModel($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        };
        break;
    default:
        echo "<br><mark> app.php > switch default / Not a 'quote' or 'dynamic-client-add' action.</mark><br>";
}

if ($clientAction) {
    echo "<br><mark> app.php > if(clientAction) / There was a client action. </mark><br>";
    $app["append-report.model"] = function ($app) {
        $config = $app['config'];
        if (empty($config['lab916_backend'])) {
            throw new \DomainException("lab916_backend not configured");
        }

        $mysql_dsn = AmazonReportsModel::getMysqlDsn(
            $config['cloudsql_database_name'],
            $config['cloudsql_port'],
            getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
        );

        return new AmazonReportsModel($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
    };
} else {
    echo "<br><mark> app.php > if(clientAction) / There was no 'Client Action' </mark><br>";
}


// Turn on debug locally
if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', 'fe80::1', '::1']) || php_sapi_name() === 'cli-server') {
    $app['debug'] = true;
} else {
    $app['debug'] = filter_var(getenv('BOOKSHELF_DEBUG'), FILTER_VALIDATE_BOOLEAN);
}

return $app;