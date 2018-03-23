<?php

echo "<br>At the very top of app.php<br><br>";

use Lab916\Cloud\Reports\DataModel\UpdateFbaReports;
use Symfony\Component\Yaml\Yaml;

echo "<br><br>below The 'use' statements<br><br>";

$app = [];

$config = getenv('BOOKSHELF_CONFIG') ?: __DIR__ . '/../config/' . 'settings.yml';
echo "<br><br>config var = $config <br><br>";
print_r($config);

$labConfig = [
    
];

//$app['yamlConfig'] = Yaml::parse(file_get_contents($config)); // this way breaks
$app['config'] = $labConfig;
// echo "<br><br>app['config'] =<br><br>";
print_r($app['config']);

$app['fba.reports.model'] = function ($app) {
    echo "<br> - LAB 916 in app['fba.reports.model'] = function() - ";

    $config = $app['config'];

    if (empty($config['lab916_backend'])) {
        throw new \DomainException('"lab916_backend" needs to be defined in settings.yaml');
    }

    $mysql_dsn = UpdateFbaReports::getMysqlDsn(
        $config['cloudsql_database_name'],
        $config['cloudsql_port'],
        getenv('GAE_INSTANCE') ? $config['cloudsql_connection_name'] : null
    );

    return new UpdateFbaReports($mysql_dsn, $config['cloudsql_user'], $config['cloudsql_password']);
};

return $app;
