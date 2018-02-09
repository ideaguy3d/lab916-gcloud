<?php

/**
 * Create a new Silex Application with Twig.  Configure it for debugging.
 * Follows Silex Skeleton pattern.
 */

use Google\Cloud\Samples\Bookshelf\DataModel\Sql;
use Lab916\Cloud\Quote\DataModel\SqlQuoteLab916;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\Yaml\Yaml;

$app = new Application();

// register twig
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../templates',
    'twig.options' => array(
        'strict_variables' => false,
    ),
));

// parse configuration
$config = getenv('BOOKSHELF_CONFIG') ?: __DIR__ . '/../config/' . 'settings.yml';

$app['config'] = Yaml::parse(file_get_contents($config));

$app['bookshelf.model'] = function ($app) {
    /** @var array $config */
    $config = $app['config'];
    if (empty($config['bookshelf_backend'])) {
        throw new \DomainException('"bookshelf_backend" must be set in bookshelf config');
    }

    // Data Model
    switch ($config['bookshelf_backend']) {
        case 'mysql':
            $mysql_dsn = Sql::getMysqlDsn(
                $config['cloudsql_database_name'],
                $config['cloudsql_port'],
                getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
            );
            return new Sql($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
        default:
            throw new \DomainException("Invalid \"bookshelf_backend\" given: $config[bookshelf_backend]. "
                . "Possible values are mysql, postgres, mongodb, or datastore.");
    }
};

$app['quote.model'] = function ($app) {
    $config = $app['config'];
    if (empty($config['bookshelf_backend'])) {
        throw new \DomainException('"bookshelf_backend" must be set in bookshelf config');
    }

    $mysql_dsn = SqlQuoteLab916::getMysqlDsn(
        $config['cloudsql_database_name'],
        $config['cloudsql_port'],
        getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
    );
    echo "above return statement, " . $mysql_dsn;
    return new SqlQuoteLab916($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
};

// Turn on debug locally
if (in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', 'fe80::1', '::1']) || php_sapi_name() === 'cli-server') {
    $app['debug'] = true;
} else {
    $app['debug'] = filter_var(getenv('BOOKSHELF_DEBUG'), FILTER_VALIDATE_BOOLEAN);
}

// add service parameters
$app['bookshelf.page_size'] = 10;

return $app;
