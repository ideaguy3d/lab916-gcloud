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

$action = isset($_GET["action"]) ? $_GET["action"] : null;
$client = isset($_GET["client"]) ? $_GET["client"] : null;

if ($action === 'gcloud-create-report' and $client === 'ptp') {
    $model = $app['ptp-report.model']($app);
    $labReportId = $model->createPtpGetReport($ptpFbaReport);

    echo "<br>Action = $action<br><br>";
    echo "Result = $labReportId";
}

// ?mws-auth-token=amzn.mws.eab0dfe5-9c2b-743b-6f84-05e4348b9f3f&merchant-id=A328KHL2CSCCRL
// will scrape the Prime Time Packaging report site.
function scrapePtpReport() {
    // going to get query string values from 'client_info' table
    $labResource = "http://mws.lab916.space/src/MarketplaceWebService/api/ptp-report.php";
    $report1 = file_get_contents($labResource);
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