<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/2/2018
 * Time: 1:52 PM
 */

include __DIR__ . "/php/connect.php";

$error = "";
$actionGoogle = isset($_GET['action']) ? $_GET['action'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
$realEmail = mysqli_real_escape_string($link, $validEmail);
$userId = isset($_GET['user-id']) ? $_GET['user-id'] : '';

if($actionGoogle == 'google') {
    // a hardcoded password at the moment
    $someRandomId = rand(0, 1000000);
    $userQ = "INSERT INTO users (user_id, email, pass) VALUES ('$someRandomId','$realEmail', 'jiha89')";

    if (mysqli_query($link, $userQ)) {
       echo " | user ".$realEmail." have been created | ";
    } else {
        echo " | ERROR: " . $userQ . ", ".mysqli_error($link)." <:| ";
    }
    echo " | in actionGoogle end point | ";
}

