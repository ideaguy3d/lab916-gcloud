<?php
/**
 * Created by PhpStorm.
 * User: Julius Alvarado
 * Date: 1/31/2018
 * Time: 11:45 AM
 */

// localhost:3306
$server = "35.226.160.11"; // 127.0.0.1:3306
$user = "lab916_user1"; //
$pw = "jiha1989";
$db = "lab916_db1";

$link = mysqli_connect($server, $user, $pw, $db);

if(!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

$hubspotConnectId = '5a880d43-3d88-4ec8-a46d-8e9ef05000f1';



