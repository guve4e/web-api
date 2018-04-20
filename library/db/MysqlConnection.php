<?php

require_once ("Connection.php");

class MysqlConnection implements Connection
{
    /**
     * @var
     */
    private $connection;

    /**
     * @var
     * @readwrite
     */
    private $isConnected = false;

    /**
     * __construct
     *
     * @access public
     * @throws Exception
     */
    public function __construct()
    {
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
    private function isValidService()
    {
        $isEmpty = empty($this->connection);                  // check if initialized object
        $isInstance = $this->connection instanceof \MySQLi;   // check if it is MysSQLi object

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
     * @throws Exception
     */
    public function connect()
    {
        // set up
        $host = DB['host'];
        $username = DB['username'];
        $password = DB['password'];
        $schema = DB['schema'];

        if ($this->isValidForConnection())
        {
            try {
                // make mysqli object
                $this->connection = new mysqli($host, $username, $password, $schema);

                $this->isConnected = true;
                if ($this->connection->connect_error) throw new DatabaseException("Error connecting to database");
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
    public function disconnect()
    {
        $close_result = false;

        if ($this->isValidForDisconnection())
        {
            $close_result = $this->connection->close();
            $this->isConnected = false;

            if ($close_result == false) throw new DatabaseException("Unsuccessful closing of database");
        }

        return $this;
    }

    /**
     * Returns the auto generated id used in the last query
     * @return integer ID of the last row
     * to be inserted
     * @throws Exception
     */
    public function getLastInsertId()
    {
        if (!$this->isValidService())
            throw new DatabaseException("Not connected to a valid service");

        return $this->connection->insert_id;
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
            throw new DatabaseException("Not connected to a valid service");

        return $this->connection->affected_rows;
    }

    /**
     * Gets the number of affected rows in a previous MySQL operation
     * @return string, the last error that occurred
     * @throws Exception
     */
    public function getLastError()
    {
        if (!$this->isValidService())
            throw new DatabaseException("Not connected to a valid service");

        return $this->connection->error;
    }

    public function query(string $sql)
    {
       return  $this->connection->query($sql);
    }

    public function multiQuery(string $sql)
    {
        return $this->connection->multi_query($sql);
    }

    public function storeResult()
    {
        return $this->connection->store_result();
    }

    public function nextResult()
    {
        return $this->connection->next_result();
    }

    public function moreResult()
    {
        return $this->connection->more_result();
    }
}