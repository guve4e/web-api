<?php

/**
* NoAuthentication
*/
require_once (AUTHORIZATION_PATH . "/Authorization.php");

class NoAuthorization extends Authorization
{
    function __construct()
    {
        parent::__construct();
    }

    function authorize($var)
    {
        return true;
    }
}
