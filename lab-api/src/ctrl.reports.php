<?php
/**
 * Created by PhpStorm.
 * User: Lab916
 * Date: 2/13/2018
 * Time: 4:41 PM
 */

$action = isset($_GET["action"]) ? $_GET["action"] : null;
$dataReport1 = scrapeReport1();

if($action === 'show-report') {
    print_r($dataReport1);
}

if($action === 'test1') {
    testGetReport($dataReport1);
}

if($action === 'gcloud-create-report1') {
    $model = $app['report.model']($app);
    $labReportId = $model->createGetReport($dataReport1);
    echo "lab GetReport creation id = $labReportId";
}

function testGetReport($reports) {
    for($row = 0; $row < count($reports); $row++) {
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

function scrapeReport1() {
    $report1 = file_get_contents("http://lab916.wpengine.com/mws/src/MarketplaceWebService/api/report1.php");
    $explode1 = explode('<h2>Report Contents</h2>', $report1);
    $explode2b = explode('  ', $explode1[1]);

    // ---------- $rows does work ----------
    $rows = explode("\n", $explode2b[0]);

    $amazonRowsFba = [];
    for ($i=0; $i<count($rows); $i++) {
        $row = preg_replace('/\t/', '|', $rows[$i]);
        $rowArray = explode('|', $row);
        $amazonRowsFba[$i] = $rowArray;
    }

    $amazonRowsFbaClean = []; $idx = 0;
    for ($i = 0; $i<count($amazonRowsFba); $i++) {
        $tempA = array();
        foreach ($amazonRowsFba[$i] as $record) {
            if(str_word_count($record) > 0) {
                $tempA[$idx] = $record;
            }
            $idx++;
        }
        $amazonRowsFbaClean[$i] = $tempA;
        $idx = 0;
    }
    return $amazonRowsFbaClean;
}