<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/27/2018
 * Time: 2:54 PM
 *
 * This controller will append new data from our Amazon MWS script
 *
 * IMPORTANT:
 * I will really need to do DB Admin work to clean up and audit
 * our data via LINQPad to ensure it's what we expect it to be.
 */


$model = $app['append-report.model']($app);

echo "<br> ( <h1>Client Action = $clientAction</h1> ) <br>";

// q string value
$clientInfo = $model->getAmwsCredentials($clientAction);

$mwsAuthKey = isset($clientInfo["mws_auth_token"]) ? $clientInfo["mws_auth_token"] : null;
$merchantId = isset($clientInfo["seller_id"]) ? $clientInfo["seller_id"] : null;

$reportData = scrapeAmazonMwsFbaReport($merchantId, $mwsAuthKey);

// Append the new data
$labReportId = $model->createReport($reportData, $clientInfo['table_name']);

echo "<br><br> ( <h2>append.ctrl.php -- client info =</h2>";
print_r($clientInfo);
echo "<br><br> Result = $labReportId ) <br><br>";

// TODO: this function fails to maintain DRY principles :( fix that! Create a factory in a functions.php file
// function will dynamically scrape the AMWS FBA report site.
// ?action=majide&mws-auth-key=
function scrapeAmazonMwsFbaReport($merchantId, $mwsAuthToken) {
    $labResource = "http://mws.lab916.space/src/MarketplaceWebService/api/fba.php";
    $urlStr = $labResource . "?merchant-id=" . $merchantId . "&mws-auth-token=" . $mwsAuthToken;

    $report1 = file_get_contents($urlStr);
    //sleep(3); // give the report data a while to stream since report may be a very large str

    $explode1 = explode('<h2>Report Contents</h2>', $report1);
    $cells = explode("\t", $explode1[1]);
    $amazonRowsFbaClean = [];
    $table = [];
    $row = 0;
    $curRow = [];
    $idx = 0;

    // convert $cells to rows, each row is delimited by \r\n, currently there are thousands of cells because
    // they were all delimited by \t
    for ($i = 0; $i < count($cells); $i++) {
        $cel = $cells[$i];
        if (strpos($cel, "\r\n") !== false) {
            // there is a new line char in this cel
            $newRowAR = explode("\r\n", $cel);
            array_push($curRow, $newRowAR[0]);
            $table[$row] = $curRow;
            $curRow = [];
            $row++;
            $table[$row] = $curRow;
            array_push($curRow, $newRowAR[1]);
        } else {
            array_push($curRow, $cel);
        }
    }

    // sanitize each field in each row, get rid of fields that have gunk.
    for ($i = 0; $i < count($table); $i++) {
        $tempA = array();
        // O (n^2) <-------------------------------------------xx
        foreach ($table[$i] as $record) {
            if ((str_word_count($record) > 0) or (1 === preg_match('~[0-9]~', $record))) {
                $tempA[$idx] = $record;
            }
            $idx++;
        }
        $amazonRowsFbaClean[$i] = $tempA;
        $idx = 0;
    }

    echo "<br><br>( append.ctrl.php > scrapeAmazonMwsFbaReport() -- scrapeAmazonMwsFbaReport() did get invoked ";
    echo " && the url string = $urlStr )<br><br>";

    return $amazonRowsFbaClean;
}