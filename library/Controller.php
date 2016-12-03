<?php

include(BASE_CLASS_PATH . "/Database.php");
/**
 * Module
 *
 * The base module class. Authentication and NoAuthentication will
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
     * __default
     *
     * Every Controller needs to override this function.
     * Its function is :
     * if an event is not specified in the user's request
     * this function executes
     *
     * @abstract
     */
    abstract public function __default();

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
        return (is_object($module) &&  $module instanceof Module && $module instanceof Authentication);
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
