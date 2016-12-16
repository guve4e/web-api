<?php

include(LIBRARY_PATH . "/Controller.php");
/**
 * Authentication
 *
 * Base class of authentication classes. The controller will check to make
 * sure your controller is an instance of Authentication class.
 *
 * @see Module
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 */
abstract class Authentication extends Controller
{
    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }// end

    /**
     *
     * @abstract
     * @param $var
     * @return mixed
     */
    public abstract function authenticate($var);
   
    /**
     * __destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        parent::__destruct();
    }// end

}// end class

?>
