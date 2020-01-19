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
}


