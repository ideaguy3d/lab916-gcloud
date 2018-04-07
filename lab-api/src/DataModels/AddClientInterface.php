<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/27/2018
 * Time: 3:53 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModels;

interface AddClientInterface
{
    /**
     * Stores all the report data from the Amazon MWS GetReport request
     *
     * @param array $reportData - Should be an associative array of the report data
     *
     * @return mixed The id of the last inserted record
    **/
    public function createReport($reportData);

    /**
     * Will insert data collected from our 'Add Client' form into the client_info table
     * because when our 'Update' button is pressed our PHP uses this table to get client info
     *
     * @param array $clientInfo = Data collected from our 'Add Client' form
    **/
    public function insertIntoClientInfo($clientInfo);
}