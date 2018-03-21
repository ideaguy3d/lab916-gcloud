<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 3/21/2018
 * Time: 1:05 PM
 */

namespace Lab916\Cloud\Reports\Fba;

/**
 * Interface UpdateFbaReportsInterface
 * @package Lab916\Cloud\Reports\UpdateFbaReports
 *
 * The common model for all client report updates
**/
interface UpdateFbaReportsInterface
{
    /**
     * Fetch Html data from Lab916 AMWS web host and append that data to
     * the correct client table
     *
     * @return mixed
    **/
    public function appendFbaReport();
}