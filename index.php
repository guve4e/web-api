<?php

require_once ('relative-paths.php');
require_once (UTILITY_PATH . '/FileManager.php');
require_once (LIBRARY_PATH . '/Router.php');
require_once (EXCEPTION_PATH . '/ApiException.php');
require_once (LIBRARY_PATH . '/Logger.php');

try
{
    Logger::logServer();
    Logger::logHeaders();

    if (!isset($_SERVER['PATH_INFO']))
        throw new NoSuchControllerException("No controller specified!");

    new Router(
        new FileManager(),
        new AuthorizationFilter(new FileManager(), new RestCall("Curl", new FileManager())),
        $_SERVER['PATH_INFO']);
}
catch (NoSuchControllerException $e)
{
    $e->output();
    include(VIEW_PATH . "/controller.php");
}
catch (ApiException $e)
{
    $e->output();
}
catch (Exception $e)
{
    die($e->getMessage());
}