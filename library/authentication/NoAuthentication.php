<?php

/**
* NoAuthentication 
*
* If your module class does not require any authentication then it should
* extend from this authentication module.
* @license http://www.opensource.org/licenses/gpl-license.php
* @package Crystalpure
* @filesource
*/


abstract class NoAuthentication extends Authentication
{
    function __construct()
    {
        parent::__construct();
    }

    function authenticate($var)
    {
        return true;
    }

    function __destruct()
    {
        parent::__destruct();
    }
}

?>
