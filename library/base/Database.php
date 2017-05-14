<?php
include(RDBMS_PATH . "/Mysql.php");
include(BASE_CLASS_PATH . "/Base.php");
/**
 * Database
 * Extends the base Base class to include a database connection.
 *
 * Composition with class that is wrapper to RDBMS
 * framework
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 */
class Database
{   
    /**
     * $db
     *
     * @var mixed represents database connection
     */
    private $db;

    /**
     *
     * @var mixed
     */
    private $type = "mysql";

    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        // switch databases
        switch ($this->type)
        {
            case "mysql":
            {   // make new Mysql
                $this->db = new Mysql();
                break;
            }
            default:
            {
                throw new Exception("Invalid type");
                break;
            }
        }
        
    }// end constructor

    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
    * __destruct
    *
    * @access public
    * @return void
    */
    public function __destruct()
    {

    }// end
}

?>
