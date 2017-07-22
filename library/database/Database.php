<?php
include(DATABSE_PATH . "/MysqlConnect.php");
/**
 * Database
 */
abstract class Database
{
    /**
     * @var
     */
    private $host;

    /**
     * @var
     */
    private $username;

    /**
     * @var
     */
    private $password;

    /**
     * @var
     */
    private $schema;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        // set up
        $this->host = DB['host'];
        $this->username = DB['username'];
        $this->password = DB['password'];
        $this->schema = DB['schema'];
    }

    /**
     * Concretes must provide
     * implementation
     * @abstract
     */
    protected abstract function connect();

    /**
     * Concretes must provide
     * implementation
     * @abstract
     */
    protected abstract function disconnect();

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getSchema()
    {
        return $this->schema;
    }
}

