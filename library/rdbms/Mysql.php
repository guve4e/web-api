 <?php

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
            // extract info from configuration file
            $server = DB['host'];
            $user = DB['username'];
            $pass = DB['password'];
            $name = DB['schema'];

            // connect
            $this->service = new \MySQLi($server, $user, $pass, $name);

            // mysqli::$connect_error -- mysqli_connect_error — Returns a string a
            // escription of the last connect error
            // Object oriented style -> string $mysqli->connect_error;
            // Procedural Style -> string mysqli_connect_error ( void )
            if ($this->service->connect_error)
            {
                $error = mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                throw new Exception("Unable to connect to service: " . $error);
            }

            $this->isConnected = true;
        }

        return $this->service;
    }// end

    /**
     * Disconnect
     * Attempt to disconnect from MySQL database
     * @return mixed disconnect/connection
     */
    public function disconnect()
    {
        if ($this->isValidService())
        {
            $this->isConnected = false;
            $this->service->close();
        }

        return $this;
    }

    /**
     * Makes a query
     * @param string $sql
     * @return a corresponding query instance
     * to be used in Query class
     * @throws Exception
     * @internal param string $sql
     */
    public function query($sql)
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        return $this->service->query($sql);
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

        // pass the query to MySQL for processing
        $result = $this->service->query($sql);

        if ($result === false)
            throw new Exception("There was an error with your SQL query:");

        $rows = array();

        for ($i = 0; $i < $result->num_rows; $i++)
        {
            $rows[] = $result->fetch_array(MYSQLI_ASSOC);
        }

        return $rows;
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
     * @return the last error of occur
     * @throws Exception
     */
    public function getLastError()
    {
        if (!$this->isValidService())
            throw new Exception("Not connected to a valid service");

        return $this->service->error;
    }
}