<?php

 /**
 * User Class
 *
 *
 * @license http://www.opensource.org/licenses/gpl-license.php
 * @package library
 */
class User extends Database
{
    // change them accordingly
    public $userID;
    public $email;
    public $password;
    public $fname;
    public $lname;

    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();

    }// end constructor

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
