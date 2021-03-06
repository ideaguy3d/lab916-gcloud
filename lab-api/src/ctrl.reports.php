<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/13/2018
 * Time: 4:41 PM
 *
 * This is the "City Bicycle Group" ctrl
 * It'll append new data to its' table
 *
 */

$action = isset($_GET["action"]) ? $_GET["action"] : null;
$client = isset($_GET["client"]) ? $_GET["client"] : null;
// $dataReport1 = scrapeReport1();
$cbcFbaReport = scrapeCbcReport();

if($action === 'gcloud-create-report' and $client === 'cbc') {
    $model = $app['cbc-report.model']($app);
    $labReportId = $model->createGetReport($cbcFbaReport);
    echo "<br>Action = $action<br><br>";
    echo "Result = $labReportId";
}

if($action === 'gcloud-cbc-show-rows' and $client === 'cbc') {
   $model = $app['cbc-report.model']($app);
   $fbaRowsAll = $model->listFbaRows();
   echo "<br><br>All the FBA rows:<br>";
   print_r($fbaRowsAll);
}

// function was made just to practice iterating over a 2-dim arr.
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

// function will scrape a TEST site I made then convert the flat file data into PHP arrays.
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

// function will scrape the CBC report site then convert the flat file data into PHP arrays.
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