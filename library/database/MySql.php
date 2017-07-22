<?php

require_once ("MysqlConnect.php");
require_once ("MysqlResponse.php");
/**
 *
 */
class MySql extends  MysqlConnect
{

    /**
     * MysqlResponse Object
     * @var
     * @readwrite
     */
    private $response;

    /**
     * @var
     * Contains measured
     * time
     */
    private $chrono;

    /**
     * Executes Query.
     * Takes database access time.
     *
     * @param $sql: the sql query
     * @return mixed: the result from the query
     * @throws mysqli_sql_exception
     */
    private function executeSqlQuery($sql)
    {
        $startTime = microtime(true);
        // pass the query to MySQL for processing
        $result = $this->getService()->query($sql);
        $endTime = microtime(true);
        $this->chrono = $endTime - $startTime;

        return $result;
    }

    /**
     * TODO Break down to smaller SRP functions
     *
     * @param $sql
     * @return array
     * @throws ApiException
     */
    private function executeMultiSqlQuery($sql)
    {
        $startTime = microtime(true);

        // guard
        if (!$this->getService()->multi_query($sql)) {
            throw new ApiException("Multi Query Failed");
        }

        $results = [];
        $i = 0;
        do {
            if ($res = $this->getService()->store_result()) {

                $result = $res->fetch_all(MYSQLI_ASSOC);
                $result = array_shift($result);
                array_push($results,$result);
                $res->free();
                $i++;
            }
        } while ($this->getService()->more_results() && $this->getService()->next_result());

        $sanitizedResults = [];
        foreach($results as $result)
        {
            foreach($result as $key=>$value)
                $sanitizedResults[$key] = $value;
        }

        $endTime = microtime(true);
        $this->chrono = $endTime - $startTime;
        return $sanitizedResults;
    }

    /**
     * Execute sql query if client wants to
     * write to database.
     * Other methods are calling
     * this one to execute the query
     *
     * @param $sql string: the SQL query
     * @throws mysqli_sql_exception
     */
    private function queryWrite($sql)
    {
        $result = $this->executeSqlQuery($sql);

        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->getAffectedRows())
            ->setSqlQueryString($sql);
    }

    /**
     * Execute sql query if client wants to
     * read from database.
     * Other methods are calling
     * this one to execute the query
     *
     * @param $sql string: the SQL query
     * @throws mysqli_sql_exception
     */
    private function queryRead($sql)
    {
        $result = $this->executeSqlQuery($sql);

        $rows = array();

        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $rows[] = $result->fetch_array(MYSQLI_ASSOC);
        }

        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->getAffectedRows())
            ->setData($rows)
            ->setSqlQueryString($sql);
    }

    /**
     * Queries only one row from table.
     * TODO Cose reuse with queryRead
     * @param $sql string: the SQL query
     * @throws mysqli_sql_exception
     */
    private function queryOneRow($sql)
    {


        $result = $this->executeSqlQuery($sql);

        $rows = array();

        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $rows[] = $result->fetch_array(MYSQLI_ASSOC);
        }

        // shift array to get the first element
        // since each row will be represented as array element
        // if we query only single row, we need first element
        $rows = array_shift($rows);

        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->getAffectedRows())
            ->setData($rows)
            ->setSqlQueryString($sql);

    }

    /**
     * Execute sql query if client wants to
     * read from database.
     * Other methods are calling
     * this one to execute the query
     *
     * @param $sql string: the SQL query
     * @throws mysqli_sql_exception
     */
    private function querySingleValue($sql)
    {
        $result = $this->executeSqlQuery($sql);

        $value = mysqli_fetch_assoc($result);

        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected( $this->getAffectedRows())
            ->setData($value)
            ->setSqlQueryString($sql);
    }

    /**
     *
     *
     * TODO SUCCESS field is not right,
     * TODO Make it better
     * @param $sql
     */
    private function executeMultipleSqlQueries($sql)
    {
        $result = $this->executeMultiSqlQuery($sql);


        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->getAffectedRows())
            ->setData($result)
            ->setSqlQueryString($sql);
    }

    /**
     * MySql constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->response = new MysqlResponse();
    }

    /**
     * Generic function to call
     * the more specific type of
     * query function
     *
     * @param $sql: the sql query
     * @param $function: the name of the function
     * to be called
     * @return mixed: the result of the query
     * @throws ApiException
     */
    public function query($sql, $function)
    {
        if (!$this->isValidService())
            throw new ApiException("Not connected to a valid service");

        try
        {
            $this->$function($sql);
        }
        catch (mysqli_sql_exception $ex)
        {
            $this->response->setSuccess(false)
                ->setMessage($ex->getMessage());
        }
        catch (Exception $ex)
        {
            throw new ApiException($ex);
        }
        finally
        {
            return $this->response->getMySqlResponse();
        }
    }
}
