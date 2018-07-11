<?php

require_once ('relative-paths.php');
require_once (LIBRARY_PATH . '/Router.php');
require_once (EXCEPTION_PATH . '/ApiException.php');
include (LIBRARY_PATH . '/Logger.php');


// Log
Logger::logServer();
Logger::logHeaders();

try
{
    // if controller and parameter are given
    if (isset($_SERVER['PATH_INFO']))
        $router = new Router($_SERVER['PATH_INFO']);
    else throw new ApiException("PATH_INFO",101);

}
catch (NotAuthorizedException $e)
{
    $e->output();
    header("Location: " . VIEW_PATH . "/authorization.php");
    die();
}
catch (ApiException $e)
{
    $e->output();

    // If no controller is specified then show home page
    // Assume normal execution showing home page
    if ($e->getCode() == 101)
        include(VIEW_PATH . "/controller.php");
} catch (Exception $e) {
    die($e->getMessage());
}