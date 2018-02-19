<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/13/2018
 * Time: 4:41 PM
 */

$action = isset($_GET["action"]) ? $_GET["action"] : null;
$client = isset($_GET["client"]) ? $_GET["client"] : null;
$dataReport1 = scrapeReport1();
$cbcFbaReport = scrapeCbcReport();

if ($action === 'info') {
    phpinfo();
}

if ($action === 'show-report') {
    print_r($dataReport1);
}

if ($action === 'test1') {
    testGetReport($dataReport1);
}

if ($action === 'gcloud-create-report1' and $client === 'cbc') {
    $model = $app['report.model']($app);
    $labReportId = $model->createGetReport($cbcFbaReport);
    echo "lab GetReport creation id = $labReportId";
}

if($action === 'gcloud-cbc-report' and $client === 'cbc') {
    $model = $app['report.model']($app);
    $labReportId = $model->createGetReport($cbcFbaReport);
    echo "<br>Action = $action<br>";
    echo "lab GetReport creation id = $labReportId";
}

if($action === 'gcloud-show-')

// will simply iterate over a 2-dim arr.
function testGetReport($reports) {
    for ($row = 0; $row < count($reports); $row++) {
        echo "<p><b>Row Number $row:</b></p>";
        echo "<ol>";
        $col = 0;
        foreach ($reports[$row] as $report) {
            echo "<li>" . @$report . "</li>";
            $col++;
        }
        echo "</ol>";
    }
}

// this function will scrape a site I made then convert the flat file data into PHP arrays.
function scrapeReport1() {
    $report1 = file_get_contents("http://lab916.wpengine.com/mws/src/MarketplaceWebService/api/report1.php");
    $explode1 = explode('<h2>Report Contents</h2>', $report1);
    $cells = explode("\t", $explode1[1]);
    $amazonRowsFbaClean = [];
    $table = [];
    $row = 0;
    $curRow = [];

    for ($i = 0; $i < count($cells); $i++) {
        $cel = $cells[$i];
        if (strpos($cells[$i], "\r\n") !== false) {
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

    /* -- old stuff --
        // deals with strings
        $rows = []; $amazonRowsFba = [];
        for ($i=0; $i<count($rows); $i++) {
            $row = preg_replace("/\t/", "|", $rows[$i]);
            $rowArray = explode("|", $row);
            $amazonRowsFba[$i] = $rowArray;
        }


    */

    $idx = 0;
    for ($i = 0; $i < count($table); $i++) {
        $tempA = array();
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

// this function will scrape a site I made then convert the flat file data into PHP arrays.
function scrapeCbcReport() {
    $report1 = file_get_contents("http://lab916.wpengine.com/mws/src/MarketplaceWebService/api/cbcReport.php");
    $explode1 = explode('<h2>Report Contents</h2>', $report1);
    $cells = explode("\t", $explode1[1]);
    $amazonRowsFbaClean = [];
    $table = [];
    $row = 0;
    $curRow = [];

    for ($i = 0; $i < count($cells); $i++) {
        $cel = $cells[$i];
        if (strpos($cells[$i], "\r\n") !== false) {
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

    $idx = 0;
    for ($i = 0; $i < count($table); $i++) {
        $tempA = array();
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