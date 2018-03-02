<?php
/**
 * Created by PhpStorm.
 * User: Julius Alvarado
 * Date: 2/1/2018
 * Time: 10:46 PM
 */

require __DIR__ . "/functions.php";

$error = "";
$action = array_key_exists('action', $_GET) ? $_GET['action'] : "";
$email = array_key_exists('email', $_GET) ? $_GET['email'] : "";
$validEmail = @mysqli_real_escape_string($link, filter_var($email, FILTER_VALIDATE_EMAIL));
$loginActive = array_key_exists('loginActive', $_GET) ? $_GET['loginActive'] : "";

if ($action) {
    if ($action === "loginSignup") {
        echo " - jha - in the loginSignup endpoint (: ";

        if (!$validEmail) {
            $error = "you need a valid email";
        }

        if ($error != "") {
            echo $error;
            exit();
        }

        if ($loginActive == "0") {
            echo "in loginActive";
            $getUserQue = "SELECT * FROM users WHERE email = '$validEmail' LIMIT 1";
            $result = mysqli_query($link, $getUserQue);
            echo "<br>";
            echo print_r($result);
            echo "<br>";
            if (mysqli_num_rows($result) > 0) {
                $error = "That email is already used";
                echo "There should be an error...";
            }
        }

        if ($error != "") {
            echo $error;
            exit();
        }
    }
    else if ($action == "postNewUser") {
        try {
            $randPass = rand(0, 10000);
            $sql = "INSERT INTO users (email, pass) VALUES ('$validEmail', '$randPass')";
            $conn->exec($sql);
            echo "New user created ^_^";
        } catch (PDOException $e) {
            echo $sql . " Error /: " . $e->getMessage();
        }
    }
}

