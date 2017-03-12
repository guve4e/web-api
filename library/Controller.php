<?php

include(BASE_CLASS_PATH . "/Database.php");
/**
 * Controller
 *
 * The base controller class. Authentication and NoAuthentication will
 * extend this class.
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 */

abstract class Controller extends Database
{
    /**
     * $name
     *
     * The name of the controller
     *
     * @var string $name Name of module class
     */
    protected $name;

    /**
     * $json_data
     *
     * The data sent to the controller
     * via POST,PUT,DELETE methods
     *
     * @var
     */
    protected $json_data;


    /**
     * Controller constructor.
     *
     * @param $json_data
     */
    public function __construct()
    {
        parent::__construct();
        // set name at runtime
        $this->name = $this->ref->getName();

    }// end constructor

    /**
     * authorize
     *
     * Checks if the the derived class is:
     * - object
     * - instance of Controller
     *
     * @static
     * @access public
     * @param mixed $module
     * @return bool
     */
    public static function authorize($module)
    {
        return (is_object($module) &&  $module instanceof Controller);
    }

    /**
     * getJsonData
     *
     * Getter for json_data member
     *
     * @return mixed
     */
    public function getJsonData()
    {
        return $this->json_data;
    }

    /**
     * __destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        parent::__destruct();
    }

}// end class

?>
