<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/27/2018
 * Time: 3:24 PM
 */

$clientName = isset($_GET["client-name"]) ? $_GET["client-name"] : null;
$sellerId = isset($_GET["table-name"]) ? $_GET["table-name"] : null;
$mwsAuthKey = isset($_GET["mws-auth-key"]) ? $_GET["mws-auth-key"] : null;
$merchantId = isset($_GET["merchant-id"]) ? $_GET["merchant-id"] : null;

$reportData = scrapeAmazonMwsFbaReport($merchantId, $mwsAuthKey);

//-- Invoke Functions:
echo " ( lab916 - recId = " . createTable($app, $clientName) . " ) ";
// createReport($app);

function createTable($app, $reportData) {
    $model = $app["dynamic-client-add.model"]($app);
    $labReportId = $model->createReport($reportData);
    return " - ID Of last created report = $labReportId - ";
}

function createReport($app) {
    $model = $app["dynamic-client-add.model"]($app);
}

// function will scrape the Prime Time Packaging report site.
function scrapeAmazonMwsFbaReport($merchantId, $mwsAuthToken) {
    $labResource = "http://mws.lab916.space/src/MarketplaceWebService/api/fba.php";
    $report1 = file_get_contents($labResource . "?merchant-id=" . $merchantId . "&mws-auth-token=" . $mwsAuthToken);
    $explode1 = explode('<h2>Report Contents</h2>', $report1);
    $cells = explode("\t", $explode1[1]);
    sleep(4); // give the report data a while to stream since report may be a very large str
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

    return $amazonRowsFbaClean;
}