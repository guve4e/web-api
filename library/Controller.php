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
     * @var string $name Name of module class
     */
    public $name;

    /**
     * __construct
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->name = $this->ref->getName();
    }// end constructor

    /**
     * Authorize
     *
     * Checks if the the derived class is:
     * - object
     * - instance of Module
     * - instance of Authentication
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
