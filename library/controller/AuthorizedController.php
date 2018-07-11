<?php

include (LIBRARY_PATH . "/controller/Controller.php");
/**
 * Every class extending this one
 * must implement the authorize method.
 * Concrete classes have to del with
 * implementing the method.
 */
abstract class AuthorizedController extends Controller
{

    /**
     * __construct
     *
     * @access public
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct(new File());
    }

    /**
     *
     * @abstract
     * @param $var
     * @return mixed
     */
    public abstract function authorize($var);

}


