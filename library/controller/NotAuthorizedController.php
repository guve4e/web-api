<?php

require_once (AUTHORIZATION_PATH . "/AuthorizedController.php");

class NotAuthorizedController extends AuthorizedController
{
    /**
     * @param FileManager $fileManager
     * @param RestCall $restCall
     * @param $var
     * @return bool|mixed
     */
    function authorize(FileManager $fileManager, RestCall $restCall, $var)
    {
        return true;
    }
}
