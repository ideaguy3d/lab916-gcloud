<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/26/2018
 * Time: 1:46 PM
 *
 * This is the "Majide" ctrl, it'll append new data to the table
 *
 */

$mwsAuthKey = isset($_GET["mws-auth-key"]) ? $_GET["mws-auth-key"] : null;
$merchantId = isset($_GET["merchant-id"]) ? $_GET["merchant-id"] : null;

$reportData = scrapeAmazonMwsFbaReport($merchantId, $mwsAuthKey);

$model = $app['majide-report.model']($app);
$labReportId = $model->createMajideReport($reportData);

echo "<br>Action = $action<br><br>";
echo "Result = $labReportId";


// TODO: this function fails to maintain DRY principles :( fix that! Create a factory in a functions.php file
// function will dynamically scrape the AMWS FBA report site.
// ?action=majide&mws-auth-key=
function scrapeAmazonMwsFbaReport($merchantId, $mwsAuthToken) {
    $labResource = "http://mws.lab916.space/src/MarketplaceWebService/api/fba.php";
    $urlStr = $labResource . "?merchant-id=" . $merchantId . "&mws-auth-token=" . $mwsAuthToken;

    $report1 = file_get_contents($urlStr);
    sleep(3); // give the report data a while to stream since report may be a very large str

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

    echo " ( scrapeAmazonMwsFbaReport() did get invoked";
    echo " && the url string = $urlStr) ";

    return $amazonRowsFbaClean;
}