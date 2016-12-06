<?php
include(LIBRARY_PATH . '/Logger.php');

/* Log */
if($config['debug'])
{
    $l = new Logger("CONTROLLER.txt");         // make object
    $l->MakeTitleLine("START CONTROLLER");     // add row

    $h = new Logger("HEADERS.txt");
    $h->MakeTitleLine("START HEADERS");


    // log server array
    $l->PrintArray($_SERVER, "SERVER");
// log post array
    $l->PrintArray($_POST, "POST");
// log headers
    $h->PrintArray(getallheaders(), "HEADERS");


}


