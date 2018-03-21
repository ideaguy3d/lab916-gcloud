<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 2/27/2018
 * Time: 3:53 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModel;

interface AddClientInterface
{
    /**
     * Stores all the report data from the Amazon MWS GetReport request
     *
     * @param array $reportData - Should be an associative array of the report data
     *
     * @return mixed The id of the last inserted record
     */
    public function createReport($reportData);
}