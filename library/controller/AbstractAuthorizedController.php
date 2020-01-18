<?php

require_once (LIBRARY_PATH . "/controller/Controller.php");

abstract class AbstractAuthorizedController extends Controller
{
    /**
     * AbstractAuthorizedController constructor.
     * @throws ApiException
     */
    public function __construct()
    {
        parent::__construct(new FileManager());
    }

    /**
     * @param FileManager $fileManager
     * @param RestCall $restCall
     * @param $var
     * @return mixed
     */
    public abstract function authorize(FileManager $fileManager, RestCall $restCall, $var);
}

