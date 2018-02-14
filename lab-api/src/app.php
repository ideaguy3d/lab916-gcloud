<?php

use Google\Cloud\Samples\Bookshelf\DataModel\Sql;
use Lab916\Cloud\Quote\DataModel\SqlQuoteLab916;
use Lab916\Cloud\Amazon\Mws\Reports\DataModel\AmazonReportsModel;
use Symfony\Component\Yaml\Yaml;

$app = [];

$config = getenv('BOOKSHELF_CONFIG') ?: __DIR__ . '/../config/' . 'settings.yml';

$app['config'] = Yaml::parse(file_get_contents($config));

$app['bookshelf.model'] = function ($app) {
    /** @var array $config * */
    $config = $app['config'];

    if (empty($config['bookshelf_backend'])) {
        throw new \DomainException('"bookshelf_backend" must be set in bookshelf config');
    }

    echo "bookshelf_backend = " . $config['bookshelf_backend'];
    // Data Model
    switch ($config['bookshelf_backend']) {
        case 'mysql':
            $mysql_dsn = Sql::getMysqlDsn(
                $config['cloudsql_database_name'],
                $config['cloudsql_port'],
                getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
            );
            return new Sql(
                $mysql_dsn,
                $config['cloudsql_user'],
                $config['cloudsql_password']
            );
        default:
            throw new \DomainException("Invalid \"bookshelf_backend\" given: $config[bookshelf_backend]. "
                . "Possible values are mysql, postgres, mongodb, or datastore.");
    }
};

$app['quote.model'] = function ($app) {
    $config = $app['config'];
    if (empty($config['quote_backend'])) {
        throw new \DomainException('quote_backend must be configured');
    }

    $mysql_dsn = SqlQuoteLab916::getMysqlDsn(
        $config['cloudsql_database_name'],
        $config['cloudsql_port'],
        getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
    );

    return new SqlQuoteLab916($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
};

// The Amazon MWS Report API
$app["report.model"] = function ($app) {
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

// Turn on debug locally
if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', 'fe80::1', '::1']) || php_sapi_name() === 'cli-server') {
    $app['debug'] = true;
} else {
    $app['debug'] = filter_var(getenv('BOOKSHELF_DEBUG'), FILTER_VALIDATE_BOOLEAN);
}

return $app;