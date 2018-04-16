<?php

echo "<br>At the very top of app.php<br><br>";

use Lab916\Cloud\Reports\DataModel\UpdateFbaReports;
use Symfony\Component\Yaml\Yaml;

echo "<br><br>below The 'use' statements<br><br>";

$app = [];

$config = getenv('BOOKSHELF_CONFIG') ?: __DIR__ . '/../config/' . 'settings.yml';
echo "<br><br>config var = $config <br><br>";
print_r($config);

// made a change...
// made another change
$labConfig = [
    "google_client_id" => "YOUR_CLIENT_ID",
    "google_client_secret" => "YOUR_CLIENT_SECRET",
    "google_project_id" => "labdata-916",
    "lab916_backend" => "mysql",
    "cloudsql_connection_name" => "labdata-916:us-west1:lab916",
    "cloudsql_database_name" => "lab916",
    "cloudsql_user" => "lab-admin",
    "cloudsql_password" => "jiha1989",
    "cloudsql_port" => 3306
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
