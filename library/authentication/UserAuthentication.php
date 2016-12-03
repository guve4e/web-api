<?php
include(AUTHENTICATION_PATH . "/Authentication.php");

/**
* UserAuthentication
*
* If your module class requires that a user be logged in in order to access
* it then extend it from this Auth class. 
*
* @license http://www.opensource.org/licenses/gpl-license.php 
* @package Framework
* @filesource
*/

    abstract class UserAuthentication extends Authentication
    {   
        /**
        * API token
        * @var
        */
        private $token;

        /**
        * __construct
        * 
        * @access protected 
        */
        function __construct()
        {
            parent::__construct();
            $this->token = "Kjbd43n#4hvsoyjYSk1!UIerJdS073%dfrR";
    
        }

        /**
        * If provided good token true, else false
        * @access protected 
        */
        function authenticate($clientToken)
        {
            if($this->token == $clientToken)
                return true;
            else
                return false;
        }

        /**
        * __destruct
        * 
        * @access protected
        */
        function __destruct()
        {
            parent::__destruct();
        }
    }

?>
