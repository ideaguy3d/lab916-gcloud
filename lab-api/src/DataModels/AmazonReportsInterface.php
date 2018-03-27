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
     * After the initial create op this function becomes an append
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
     * @param string $tableName - tableName will get initialized from the getAmwsCredentials()
     *                          and passed in as a param from the ctrl
     **/
    public function createMajideReport($reportData, $tableName);

    /**
     * Will create report for "Prime Time Packaging" then start to append thereafter.
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

    /**
     * In order to dynamically get Amazon MWS report data query our 'client_info' table
     *
     * @param string $clientName - client whose authToken && merchantId is needed
     * @return array - returns an assoc.ar of the client row
    **/
    public function getAmwsCredentials($clientName);
}

