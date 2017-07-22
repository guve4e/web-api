<?php
require_once (EXCEPTION_PATH . "/DatabaseException.php");
require_once ("MysqlResponse.php");
require_once ("Database.php");

/**
 * Mysql class uses MySQL predefined functions
 *
 */
class MysqlConnect extends Database
{
    /**
     * @var
     */
    private $service;

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
        parent::__construct();



        // connect to database
        $this->connect();

        // adjust myqsli to throw exceptions
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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
        return $isInstance && !$isEmpty;
    }

    /**
     * Condition for connection to
     * database
     * @return bool
     */
    private function isValidForConnection()
    {
        return !$this->isConnected;
    }

    /**
     * Condition for disconnection to
     * database
     * @return bool
     */
    private function isValidForDisconnection()
    {
        return $this->isValidService() && $this->isConnected;
    }

    /**
     * Makes A Connection
     * @return MySQLi
     * @throws Exception
     */
    protected function connect()
    {

        if ($this->isValidForConnection())
        {
            try {

                // make mysqli object
                $this->service = new mysqli($this->getHost()
                    , $this->getUsername(), $this->getPassword(), $this->getSchema());

                $this->isConnected = true;
                if ($this->service->connect_error) throw new Exception("Error connecting to database");
            } catch (mysqli_sql_exception $e) {
                $this->isConnected = false;
                throw new DatabaseException($e->getMessage());
            }
            catch (Exception $ex) {
                $this->isConnected = false;
                throw new ApiException($ex);
            }
        }
    }

    /**
     * Disconnect
     * Attempt to disconnect from MySQL database
     * @return mixed disconnect/connection
     * @throws Exception
     */
    protected function disconnect()
    {
        // initial result
        $close_result = false;

        if ($this->isValidForDisconnection())
        {
            $close_result = $this->service->close();
            $this->isConnected = false;

            if ($close_result == false) throw new Exception("Unsuccessful closing of database", 777);
        }

        return $this;
    }

    /**
     * Returns the auto generated id used in the last query
     * @return integer ID of the last row
     * to be inserted
     * @throws Exception
     */
    protected function getLastInsertId()
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
    protected function getAffectedRows()
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
    protected function getLastError()
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        return $this->service->error;
    }

    /**
     * Returns the mqsqli instance
     * @return mixed
     */
    protected function getService()
    {
        return $this->service;
    }
}