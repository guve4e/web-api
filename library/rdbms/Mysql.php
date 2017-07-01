<?php
require_once (EXCEPTION_PATH . "/DatabaseException.php");
/**
 * Mysql class uses MySQL predefined functions
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 * @filesource
 */
class Mysql
{
    /**
     * @var
     */
    private $service;

    /**
     * @var
     * @readwrite
     */
    private $host;

    /**
     * @var
     * @readwrite
     */
    private $username;

    /**
     * @var
     * @readwrite
     */
    private $password;

    /**
     * @var
     * @readwrite
     */
    private $schema;

    /**
     * @var
     * @readwrite
     */
    private $port = "3306";

    /**
     * @var
     * @readwrite
     */
    private $charset = "utf8";

    /**
     * @var
     * @readwrite
     */
    private $engine = "InnoDB";

    /**
     * @var
     * @readwrite
     */
    private $isConnected = false;

    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        // set up
        $this->host = DB['host'];
        $this->username = DB['username'];
        $this->password = DB['password'];
        $this->schema = DB['schema'];

        // adjust myqsli to throw exceptions
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // connect to database
        $this->connect();
    }

    /**
     * Checks if connected to the database.
     * Makes sure that it is connected, is instance and
     * it is initialized.
     * @return true if valid connection false, other ways
     */
    protected function isValidService()
    {
        $isEmpty = empty($this->service);                  // check if initialized object
        $isInstance = $this->service instanceof \MySQLi;   // check if it is MysSQLi object

        // if these conditions are met return true, else false
        return $this->isConnected && $isInstance && !$isEmpty;
    }// end

    /**
     * Makes A Connection
     * @return MySQLi
     * @throws Exception
     */
    public function connect()
    {
        if (!$this->isValidService())
        {
            try {

            //    $this->service = new mysqli($this->host, $this->username, $this->password, $this->schema);

                $this->isConnected = true;
            } catch (mysqli_sql_exception $e) {
                throw new DatabaseException($e->getMessage());
            }

            $this->isConnected = true;
        }

        return $this->service;
    }// end

    /**
     * Wrapper over execute()
     * It retrieves one Row from Table
     */
    public function getOneRow($sql)
    {
        // check precondition
        if ($sql == null) throw new Exception("Null SQL Query");
        // execute
        $result = $this->execute($sql);
        // shift array and return
        return array_shift($result);
    }

    /**
     * Executes the provided SQL statement
     * @param string query
     * @return mixed representing a row
     * @see query()
     * @throws Exception
     */
    public function execute($sql)
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        try
        {
            // pass the query to MySQL for processing
            $result = $this->service->query($sql);
        }
        catch (Exception $ex)
        {
            throw new DatabaseException("There was an error with your SQL query:");
        }

        $rows = array();

        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $rows[] = $result->fetch_array(MYSQLI_ASSOC);
        }

        return $rows;
    }

    /**
     * Disconnect
     * Attempt to disconnect from MySQL database
     * @return mixed disconnect/connection
     * @throws Exception
     */
    public function disconnect()
    {
        // initial result
        $close_result = false;

        if ($this->isValidService())
        {
            $close_result = $this->service->close();
            $this->isConnected = false;

            if ($close_result == false) throw new Exception("Unsuccessful closing of database", 777);
        }

        return $this;
    }

    /**
     * Makes a query
     * @param string $sql
     * @return string corresponding query instance
     * to be used in Query class
     * @throws Exception
     * @internal param string $sql
     */
    public function query($sql)
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        try
        {
            // pass the query to MySQL for processing
            $result = $this->service->query($sql);
        }
        catch (Exception $ex)
        {
            throw new DatabaseException("There was an error with your SQL query:");
        }
        return $result;
    }

    /**
     * Returns the auto generated id used in the last query
     * @return the ID of the last row
     * to be inserted
     * @throws Exception
     */
    public function getLastInsertId()
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        return $this->service->insert_id;
    }

    /**
     * Returns the number of rows affected
     * by the last SQL query executed
     * @return mixed
     * @throws Exception
     */
    public function getAffectedRows()
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        return $this->service->affected_rows;
    }

    /**
     * Gets the number of affected rows in a previous MySQL operation
     * @return string, the last error that occured
     * @throws Exception
     */
    public function getLastError()
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        return $this->service->error;
    }

    /**
     * __destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        try {
            $log = $this->disconnect();
        } catch (Exception $e) {
            Logger::logMsg("EXCEPTIONS",$e->getMessage());
        }
    }
}