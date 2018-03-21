<?php

$modelFbaReport = $app["fba.reports.model"]($app);

// Model method to get each client table name, merchant id, and mws key
$clientsAR = $modelFbaReport->listClientInfo();

for ($i=0; $i<count($clientsAR); $i++) {
    $clientsAR['clients'][$i]

    // Real report data
    $reportData = scrapeAmazonMwsFbaReport($merchantId, $mwsAuthKey);

    // Append new report data to appropriate client table
    $result = $modelFbaReport->appendFbaReports($tableName);
}



echo "<br> Last inserted ID = $result <br>";

// function will scrape the Prime Time Packaging report site.
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