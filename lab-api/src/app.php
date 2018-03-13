<?php

use Lab916\Cloud\Quote\DataModel\SqlQuoteLab916;
use Lab916\Cloud\Amazon\Mws\Reports\DataModel\AmazonReportsModel;
use Lab916\Cloud\Amazon\Mws\Reports\DataModel\AddClientModel;
use Symfony\Component\Yaml\Yaml;

$app = [];

$config =  __DIR__ . '/../config/' . 'settings.yml';

$app['config'] = Yaml::parse(file_get_contents($config));

// figure out how to implement this using an associative array.
switch ($action) {
    case "dynamic-client-add":
        // Dynamically adding client reports via a form
        $app["dynamic-client-add.model"] = function ($app) {
            $config = $app['config'];
            $clientName = isset($_GET["client-name"]) ? $_GET["client-name"] : null;

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
    case "quote":
        // The data model for the interactive quote questionnaire I built.
        $app['quote.model'] = function ($app) {
            $config = $app['config'];
            if (empty($config['quote_backend'])) {
                throw new \DomainException('quote_backend must be configured');
            }

            $mysql_dsn = SqlQuoteLab916::getMysqlDsn($config['cloudsql_database_name'], $config['cloudsql_port'], getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null);

            return new SqlQuoteLab916($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        };
        break;
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
    case "prime-time-packaging":
        // The Amazon MWS Report API
        $app["cbc-report.model"] = function ($app) {
            $config = $app['config'];

            if (empty($config['lab916_backend'])) {
                throw new \DomainException('lab916_backend must be configured');
            }

            $mysql_dsn = AmazonReportsModel::getMysqlDsn(
                $config['cloudsql_database_name'],
                $config['cloudsql_port'],
                getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null);

            return new AmazonReportsModel($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        };
        break;
    case "action":
        // require __DIR__ . '';
        break;
    default:
        echo " <br> ( app.php switch default ) <br> ";
}

// Turn on debug locally
if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', 'fe80::1', '::1']) || php_sapi_name() === 'cli-server') {
    $app['debug'] = true;
} else {
    $app['debug'] = filter_var(getenv('BOOKSHELF_DEBUG'), FILTER_VALIDATE_BOOLEAN);
}

return $app;