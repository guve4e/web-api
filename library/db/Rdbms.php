<?php
/**
 * Created by PhpStorm.
 * User: guve4
 * Date: 4/19/2018
 * Time: 6:40 PM
 */

class Rdbms
{

    private $strategy = NULL;

    /**
     * RestCall constructor.
     * @param string $restCallType Rest Call Type Curl vs Socket
     * @throws Exception
     */
    public function __construct(string $rdbmsType, Connection $connection)
    {
        switch ($rdbmsType)
        {
            case "Mysql":
                $this->strategy = new Mysql($connection);
                break;
        }
    }
}