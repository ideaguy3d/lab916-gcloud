<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/27/2018
 * Time: 3:24 PM
 */

// mandatory info
$clientName = isset($_GET["client-name"]) ? $_GET["client-name"] : null;
$mwsAuthKey = isset($_GET["mws-auth-key"]) ? $_GET["mws-auth-key"] : null;
$merchantId = isset($_GET["merchant-id"]) ? $_GET["merchant-id"] : null;
// optional stuff
$description = isset($_GET["description"]) ? $_GET["description"] : null;
$information = isset($_GET["information"]) ? $_GET["information"] : null;
$notes = isset($_GET["notes"]) ? $_GET["notes"] : null;
// VERY important "client action"
$clientAction = isset($_GET["client-action"]) ? $_GET["client-action"] : null;

// Real client info Data
$clientInfo = [
    'client_name' => $clientName,
    'mws_auth_token' => $mwsAuthKey,
    'seller_id' => $merchantId,
    'description' => $description,
    'information' => $information,
    'notes' => $notes,
    'client_action' => $clientAction
];

// Real report data
// $reportData = scrapeDynamicAmazonMwsFbaReport($merchantId, $mwsAuthKey);

//-- Invoke Functions:
//createReport($app, $reportData, $clientName);
insertClientInfo($app, $clientInfo);


// ----------------------------------------------------------------------
// File relevant functions
// ----------------------------------------------------------------------

function createReport($app, $reports, $clientName) {
    $model = $app["dynamic-client-add.model"]($app, $clientName);
    $labReportId = $model->createReport($reports);
    echo "<br> ( Report Result ID = $labReportId ) <br>";
}

function insertClientInfo($app, $clientInfo) {
    $model = $app["dynamic-client-add.model"]($app, $clientInfo['client_action']);
    $result = $model->insertIntoClientInfo($clientInfo);
    echo "<br><br>LAB 916 ctrl.dynamic.client.add.php > insertClientInfo() Add client result = $result <br><br>";
}

// fails to maintain DRY principles :(
// function will dynamically scrape the AMWS FBA report site.
function scrapeDynamicAmazonMwsFbaReport($merchantId, $mwsAuthToken) {
    //$labResource = "http://mws.lab916.space/src/MarketplaceWebService/api/fba.php";
    $labResource = "http://mws.lab916.space/src/MarketplaceWebService/api/fba.php";
    $urlStr = $labResource . "?merchant-id=" . $merchantId . "&mws-auth-token=" . $mwsAuthToken;

    $report1 = file_get_contents($urlStr);
    sleep(3); // give the report data a while to stream since it may be a very large str

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