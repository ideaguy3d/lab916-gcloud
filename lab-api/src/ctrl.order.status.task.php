<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 4/10/2018
 * Time: 3:31 PM
 */

echo '<hr><br>Getting file contents from our own app on gCloud.<br>';

$zContents = file_get_contents("https://labdata-916.appspot.com/form/add.php");

echo '<br>The contents are: <br><br>';
echo "<div> $zContents </div><hr>";
