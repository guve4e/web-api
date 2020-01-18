<?php

require_once(AUTHORIZATION_PATH . "/AbstractAuthorizedController.php");

class UnAuthorizedController extends AbstractAuthorizedController
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
