<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/22/2018
 * Time: 5:16 PM
 */

$action = isset($_GET["action"]) ? $_GET["action"] : null;
$client = isset($_GET["client"]) ? $_GET["client"] : null;
$ptpFbaReport = scrapePtpReport();

if ($action === 'gcloud-ptp-create-report' and $client === 'ptp') {
    $model = $app['cbc-report.model']($app);
    $labReportId = $model->createPtpGetReport($ptpFbaReport);
    echo "<br>Action = $action<br><br>";
    echo "Result = $labReportId";
}

// function will scrape the Prime Time Packaging report site.
function scrapePtpReport() {
    $report1 = file_get_contents("http://lab916.wpengine.com/mws/src/MarketplaceWebService/api/ptp-report.php");
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

//