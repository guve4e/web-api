<?php

require_once ("Database.php");
require_once ("MysqlResponse.php");

class MySql
{
    /**
     * @var boolean
     * If set to true, MySlq Class
     * will wrap the data retrieved
     * from table with metadata.
     */
    private $verbose = true;

    /**
     * @var object
     * The data retrieved
     * from the table.
     */
    private $data;

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
     * @param $sql : the sql query
     * @return mixed: the result from the query
     * @throws mysqli_sql_exception
     * @throws DatabaseException
     */
    private function executeSqlQuery($sql)
    {
        $startTime = microtime(true);

        // pass the query to MySQL for processing
        $result = $this->connection->query($sql);

        if (is_null($result))
            throw new DatabaseException("Bad Query!");

        $endTime = microtime(true);
        $this->chrono = $endTime - $startTime;

        // save the raw data
        $this->data = $result;

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
        if (!$this->connection->multiQuery($sql)) {
            throw new ApiException("Multi Query Failed");
        }

        $results = [];
        $i = 0;
        do {
            if ($res = $this->connection->storeResult()) {

                $result = $res->fetch_all(MYSQLI_ASSOC);
                $result = array_shift($result);
                array_push($results,$result);
                $res->free();
                $i++;
            }
        } while ($this->connection->moreResult() && $this->connection->nextResult());

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
     * @throws Exception
     */
    private function queryWrite($sql)
    {
        $result = $this->executeSqlQuery($sql);

        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->connection->getAffectedRows())
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
     * @throws Exception
     */
    private function queryRead($sql)
    {
        $result = $this->executeSqlQuery($sql);

        $rows = [];

        // TODO check for null before
        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $rows[] = $this->connection->getDataSet($result);
        }

        $this->data = $result;

        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->connection->getAffectedRows())
            ->setData($rows)
            ->setSqlQueryString($sql);
    }

    /**
     * Queries only one row from table.
     * TODO reuse with queryRead
     * @param $sql string: the SQL query
     * @throws mysqli_sql_exception
     * @throws Exception
     */
    private function queryOneRow($sql)
    {
        $result = $this->executeSqlQuery($sql);

        $rows = [];

        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $rows[] = $result->fetch_array(MYSQLI_ASSOC);
        }

        // shift array to get the first element
        // since each row will be represented as array element
        // if we query only single row, we need first element
        $rows = array_shift($rows);

        $this->data = $rows;
        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->connection->getAffectedRows())
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
     * @throws Exception
     */
    private function querySingleValue($sql)
    {
        $result = $this->executeSqlQuery($sql);

        $value = mysqli_fetch_assoc($result);

        $this->data = $value;
        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->connection->getAffectedRows())
            ->setData($value)
            ->setSqlQueryString($sql);
    }

    /**
     * TODO SUCCESS field is not right,
     * TODO Make it better
     * @param $sql
     * @throws ApiException
     * @throws Exception
     */
    private function executeMultipleSqlQueries($sql)
    {
        $result = $this->executeMultiSqlQuery($sql);

        $this->response->setExecutionTime($this->chrono)
            ->setSuccess($result)
            ->setRowsAffected($this->connection->getAffectedRows())
            ->setData($result)
            ->setSqlQueryString($sql);
    }

    /**
     * MySql constructor
     * @throws DatabaseException
     */
    public function __construct(Connection $mysqlConnection)
    {
        if (!isset($mysqlConnection))
            throw new DatabaseException("Bad parameter in MySql constructor!");

        $this->connection = $mysqlConnection;
        $this->response = new MysqlResponse();
    }

    /**
     * Begins a transaction.
     * Requires MySQL 5.6 and above,
     * and the InnoDB engine.
     */
    public function startTransaction()
    {
        $this->connection->beginTransaction();
    }

    /**
     * Commit Transaction.
     */
    public function commit()
    {
        $this->connection->commit();
    }

    /**
     * Roll Back Transaction.
     */
    public function rollback()
    {
        $this->connection->rollBack();
    }

    /**
     * This method checks the success of previous
     * sql query and if the query was NOT successful
     * then it rolls-back
     * @param $msg
     */
    public function rollbackOnFail($msg)
    {
        if (!$this->response->isSuccess()) {
            $this->rollback();

            $reasonMsg = "Database Message: ". $this->response->getMessage() . "\n";
            $reasonMsg .= "Function Name: " . $msg ."\n";

            Logger::logException($reasonMsg);
        }
    }

    /**
     * @param bool $verbose
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
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
            if($this->verbose)
                return $this->response->getMySqlResponse();
            else
                return $this->data;
        }
    }

    public function getLastInsertedId()
    {
        return $this->connection->getLastInsertId();
    }
    /**
     * Disconnect
     */
    public function disconnect()
    {
        $this->connection->disconnect();
    }
}
