<?php

/**
 * NoAuthorization
 * No need for API key
 */
require_once (AUTHORIZATION_PATH . "/AuthorizedController.php");

class NotAuthorizedController extends AuthorizedController
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
