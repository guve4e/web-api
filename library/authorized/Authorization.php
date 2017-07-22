<?php

include(LIBRARY_PATH . "/Controller.php");
/**
 * Every class extending this one
 * must implement the authorize method.
 * Concrete classes have to del with
 * implementing the method.
 */
abstract class Authorization extends Controller
{

    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     * @abstract
     * @param $var
     * @return mixed
     */
    public abstract function authorize($var);

}// end class


