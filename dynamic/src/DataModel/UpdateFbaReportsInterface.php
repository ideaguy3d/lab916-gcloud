<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/21/2018
 * Time: 1:05 PM
 */

namespace Lab916\Cloud\Reports\DataModel;

/**
 * Interface UpdateFbaReportsInterface
 * @package Lab916\Cloud\Reports\UpdateFbaReports
 *
 * The common model for all client report updates
**/
interface UpdateFbaReportsInterface
{
    /**
     * Fetch Html data from Lab916 AMWS web page and append that data to
     * the correct client table
     *
     * @param array $reports - the report data scraped from LAB 916's amws page
     * @param string $tableName - the appropriate client table name to insert data into
     *
     * @return mixed
    **/
    public function appendFbaReports($reports, $tableName);


    /**
     * List all the clients and their information
     *
     * @return array ['clients_info' => an array of assoc.ar's mapping
     *                                  column name to column value]
    **/
    public function listClientInfo();
}