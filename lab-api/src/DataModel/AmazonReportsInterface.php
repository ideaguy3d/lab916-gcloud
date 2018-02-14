<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModel;

interface AmazonReportsInterface
{
    /**
     * Inserts Amazon MWS GetReport API operation data into a Lab916 db
     *
     * @param $reports
     * @param null $id
     * @return mixed - id of newly created record if post was successful
     */
    public function createGetReport($reports, $id=null);
}

