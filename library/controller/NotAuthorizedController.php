<?php

require_once (AUTHORIZATION_PATH . "/AuthorizedController.php");

class NotAuthorizedController extends AuthorizedController
{
    /**
     * @param FileManager $fileManager
     * @param $var
     * @return bool|mixed
     */
    function authorize(FileManager $fileManager, $var)
    {
        return true;
    }
}
