<?php

require_once('config.php'); // include the configuration file
require_once('log.php');
require_once (LIBRARY_PATH . '/Constructor.php');

try
{
    // construct
    if (isset($_SERVER['PATH_INFO']))  $constructor = new Constructor($_SERVER['PATH_INFO']);
    else throw new Exception("PATH_INFO");
}

catch (Exception $e)
{
    // if call is not authorized
    if ( $e->getMessage() == "Authorization")
    {
        header(VIEW_PATH . "/authentication.php");
        die();
    } // if there is no PATH_INFO, display API View
    else if ( $e->getMessage() == "PATH_INFO")
    {
        include(VIEW_PATH . '/controller.php');
    }
    else if ( $e->getMessage() == "Wrong Request")
    {
        // send notification to the user that she has wrong
        // request
        $data = [
            "message" => "Wrong Request, Check URL"
        ];
        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}