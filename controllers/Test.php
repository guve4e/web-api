<?php
include(AUTHENTICATION_PATH . "/UserAuthentication.php");

class Test extends UserAuthentication
{
    /**
     * __construct
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * GET
     */
    public function get($id)
    {

        Logger::logMsg("Test","GET");

        $data = [
            "controller" => "Test",
            "method" => "GET",
            "id" => $id
        ];

        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * POST
     */
    public function post($id)
    {


        //get the data
        $json = file_get_contents("php://input");

        Logger::logMsg("TMP",$json);

        //convert the string of data to an array
        $d = json_decode($json, true);

        Logger::logMsg("TMP",$d);



        $data = [
            "controller" => "Test",
            "method" => "POST",
            "id" => $id,
            "data" => $d
        ];

        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * PUT
     */
    public function put($id)
    {
        //get the data
        $json = file_get_contents("php://input");

        Logger::logMsg("TMP",$json);

        //convert the string of data to an array
        $d = json_decode($json, true);

        Logger::logMsg("TMP",$d);

        $data = [
            "controller" => "Test",
            "method" => "PUT",
            "id" => $id,
            "data" => $d
        ];

        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        $json = file_get_contents('php://input');
        $arr = json_decode($json,true);

        $data = [
            "controller" => "Test",
            "method" => "DELETE",
            "id" => $id,
            "data" => $arr
        ];

        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
    * __destruct
    *
    * @access public
    * @return void
    */
    public function __destruct()
    {
        parent::__destruct();
    }
}

 ?> 


