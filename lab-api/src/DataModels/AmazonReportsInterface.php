<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModels;

interface AmazonReportsInterface
{
    /**
     * Inserts City Bicycle Company Amazon MWS GetReport API operation data into a Lab916 db
     *
     * @param $reports
     * @param null $id
     * @return mixed - id of newly created record if post was successful
     */
    public function createCbcGetReport($reports, $id=null);

    /**
     * This will append new AMWS fba data that I will do DBAdmin tasks on later to sanitize
     * and audit data.
     *
     * @param array $reportData - the data that'll get scraped from our web page
     **/
    public function appendMajideReport($reportData);

    /**
     * Will create report for "Prime Time Packaging"
     *
     * @param array $reportData - the data that'll get scraped from our web page
    **/
    public function createPtpGetReport($reportData);

    /**
     * I forgot what this was for
     *
     * @param int $limit - the maximum amount to fetch
     * @param mixed $cursor - I think this is for pagination
    **/
    public function listFbaRows($limit, $cursor);
}

