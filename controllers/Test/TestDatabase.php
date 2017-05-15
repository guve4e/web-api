<?php
require_once(BASE_CLASS_PATH . "/Database.php");

/**
 * Test Database
 * Provides Test controller with database functionality
 *
 * @see https://en.wikipedia.org/wiki/Reflection_(computer_programming)
 * @package test
 */
class TestDatabase extends Database
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
     * __destruct
     *
     * @access public
     * @return void
     */
    public function __destruct()
    {
        parent::__destruct();
    }
}