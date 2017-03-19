<?php

require_once('config.php'); // include configuration file
require_once (LIBRARY_PATH . '/Constructor.php'); // include Constructor class
require_once (EXCEPTION_PATH . '/ApiException.php'); // include ApiException class
include(LIBRARY_PATH . '/Logger.php');

// Log
Logger::logServer();
Logger::logHeaders();


try
{
    // construct
    if (isset($_SERVER['PATH_INFO']))  $constructor = new Constructor($_SERVER['PATH_INFO']);
    else throw new ApiException("PATH_INFO",101);

} catch (ApiException $e) {
    // if call is not authorized
    if ($e instanceof NotAuthorizedException)
    {
        $e->output();
        header(VIEW_PATH . "/authentication.php");
        die();
    } // if there is no PATH_INFO, display API View
    else if ($e instanceof NoSuchMethodException) {
        $e->output();
    }
    else if ($e->getCode() == 101)
    {
        include(VIEW_PATH . "/controller.php");
    }// if the controller does not exist
    else if ($e instanceof NoSuchControllerException)
    {
        // send right message to client
        $e->output();
    }// if the method is not implemented
    else if ($e instanceof  MethodNotImplementedException)
    {
        $e->output();
    }
}// end try / catch