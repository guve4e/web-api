<?php

/**
 * MysqlResponse class packs information
 * to be send to the client.
 * The information consist of
 * 1. If the Database Update was successful
 * 2. The time it took to update a table/tables
 * 3. On failure, it sends the message
 * and logs.
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library/rdbms
 * @filesource
 */

require_once (LIBRARY_PATH . "/Logger.php");

class MysqlResponse
{
    /**
     *
     * @var Boolean
     */
    private $success;

    /**
     *
     * @var String
     */
    private $executionTime;

    /**
     * @var String object
     */
    private $time;

    /**
     *
     * @var String
     */
    private $message = "none";

    /**
     * @var String
     */
    private $sqlQueryString;

    /**
     * @param $number
     * @param int $precision
     * @return float|int
     */
    private function round_up($number, $precision = 2)
    {
        $fig = (int) str_pad('1', $precision, '0');
        return (ceil($number * $fig) / $fig);
    }

    /**
     * Logs the response
     */
    private function logResponse($response)
    {
        $response->time = $this->time;
        $response->sqlQuery = $this->sqlQueryString;
        $jsonStringResponse = json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        Logger::logMySqlResponse($jsonStringResponse);
    }

    /**
     *  Packs needed information
     *  from member attributes
     *  @return  StdClass object
     */
    private function createResponseObject(): StdCLass
    {
        $tmp = new StdClass;
        $tmp->success = $this->success;
        $tmp->database_access_time = $this->executionTime;

        if(!$this->success) $tmp->message = $this->message;

        return $tmp;
    }



    /**
     * Constructor
     *
     * @access public
     */
    public function __construct()
    {

    }

    /**
     * Sets Success
     *
     * @param mixed $success
     * @return MysqlResponse object
     */
    public function setSuccess($success) : MysqlResponse
    {
        // set time when the response was produced
        $this->time = date('Y-m-d H:i:s');

        // set success
        $this->success = $success;
        return $this;
    }

    /**
     * Sets Time
     *
     * @param mixed $executionTime
     * @return MysqlResponse object
     */
    public function setExecutionTime($executionTime) : MysqlResponse
    {
        $this->executionTime = $this->round_up($executionTime,4);
        return $this;
    }

    /**
     * Sets Message on Failure
     *
     * @param mixed $message
     * @return MysqlResponse object
     */
    public function setMessage($message): MysqlResponse
    {
        $this->message = $message;
        return $this;
    }

    /**
     *
     */
    public function setSqlQueryString($query): MysqlResponse
    {
        $this->sqlQueryString = $query;
        return $this;
    }

    /**
     * Collects the needed information
     * from the member attributes,
     * packs it in object and returns the
     * object.
     *
     * @return StdClass
     */
    public function getMySqlResponse() : StdClass
    {
        // create response object
       $response = $this->createResponseObject();

       // log it
        $this->logResponse($response);

        return $response;
    }

    /**
     * Destructor
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {

    }
}

