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
     * __default
     *
     * This function is ran by the controller if an event is not specified
     * in the user's request.
     *
     */
    public function __default()
    {

        $data = [
            "controller" => "Test",
            "method" => "Default"
        ];

        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));

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
        $data = [
            "controller" => "Test",
            "method" => "POST",
            "id" => $id
        ];

        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * PUT
     */
    public function put($id)
    {
        $data = [
            "controller" => "Test",
            "method" => "PUT",
            "id" => $id
        ];

        echo( json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        $data = [
            "controller" => "Test",
            "method" => "DELETE",
            "id" => $id
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


