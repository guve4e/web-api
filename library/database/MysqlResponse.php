<?php

/**
 * MysqlResponse class packs information
 * to be send to the client.
 * The information consist of
 * 1. If the Database Update was successful
 * 2. The time it took to update a table/tables
 * 3. On failure, it sends the message
 * and logs.
 * 4. The date and time of occurrence.
 * 5. The SQL query used for database access
 * 6. The number of rows affected.
 * 7. The data return by the database, if any.
 * 8. Stat - If MySQLi retrieves data from the
 * database it gives you statistics instead true/false
 * in the result field as:
 *  ex: $result = $this->service->query($sql);
 *      Here $result can contain boolean, true/false
 *      or statistics for the query.
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library/database
 * @filesource
 */

require_once (LIBRARY_PATH . "/Logger.php");

class MysqlResponse
{
    /**
     *
     * @var String
     */
    private $stats = "none";

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
     * @var
     */
    private $rowsAffected;

    /**
     *
     * @var Boolean
     */
    private $success;

    /**
     * @var array
     */
    private $data = array();

    /**
     * @param $number
     * @param int $precision
     * @return float|int
     */
    private function roundUp($number, $precision = 2)
    {
        $fig = (int) str_pad('1', $precision, '0');
        return (ceil($number * $fig) / $fig);
    }

    /**
     * Logs the response
     */
    private function logResponse()
    {
        Logger::logMySqlResponse($this->__toString());
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
        $tmp->stats = $this->stats;
        $tmp->database_access_time = $this->executionTime;
        if(!$this->success) $tmp->message = $this->message;
        if(!isset($this->data))
            $tmp->data = "No Data";
        else
            $tmp->data = $this->data;
        $tmp->rows_affected = $this->rowsAffected;

        return $tmp;
    }

    /**
     * ControllerFactory
     *
     * @access public
     */
    public function __construct()
    {

    }

    /**
     * Sets Success
     *
     * @param mixed $successOrStats
     * @return MysqlResponse object
     */
    public function setSuccess($successOrStats) : MysqlResponse
    {
        // set time when the response was produced
        $this->time = date('Y-m-d H:i:s');

        if (is_bool($successOrStats))
        {
            // set success
            $this->success = $successOrStats;
        }
        else
        {
            // set success
            $this->success = true;
            // set stats
            $this->stats = $successOrStats;
        }


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
        $this->executionTime = $this->roundUp($executionTime,4);
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
     * Sets the sql query string
     * used for the databse query
     *
     * @param $query
     * @return MysqlResponse
     */
    public function setSqlQueryString($query): MysqlResponse
    {
        $this->sqlQueryString = $query;
        return $this;
    }

    /**
     * Sets the data returned from the
     * sql query
     *
     * @param array $data
     * @return MysqlResponse
     */
    public function setData($data = Array()) : MysqlResponse
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Sets the number of rows
     * affected by the query
     *
     * @param array $num num of rows affected
     * @return MysqlResponse
     */
    public function setRowsAffected($num) : MysqlResponse
    {
        $this->rowsAffected = $num;
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
     * toString magical method
     *
     * @return string
     */
    public function __toString()
    {
        $converted_res = ($this->success) ? 'true' : 'false' ;
        $data = print_r($this->data,true);

        $toString = "Success        : " . $converted_res . "\n" .
                    "Rows Affected  : " . $this->rowsAffected . "\n" .
                    "Execution Time : " . $this->executionTime . "\n" .
                    "Time           : " . $this->time . "\n" .
                    "Message        : " . $this->message . "\n" .
                    "SQL Query      : " . $this->sqlQueryString . "\n" .
                    "Data           : " . $data . "\n";
        return $toString;
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

