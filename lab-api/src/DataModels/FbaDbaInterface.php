<?php
/**
 * Created by Julius Alvarado.
 * User: Lab916
 * Date: 4/10/2018
 * Time: 4:02 PM
 */

namespace Lab916\Cloud\Amazon\Mws\Reports\DataModels;

interface FbaDbaInterface
{
    /**
     * Detect rows with the same Amazon Order ID then check the Order Status and if there
     * are older Order Status's delete them.
     *
     * @param string $tableName - The name of the table that will get passed in from the ctrl
     *
     * @return mixed
     **/
    function OrderStatusAudit($tableName);
}