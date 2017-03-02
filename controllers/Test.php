<?php
include(AUTHENTICATION_PATH . "/UserAuthentication.php");

class Test extends UserAuthentication
{
    /**
     * __construct
     *
     * @access public
     */
    public function __construct($c)
    {
        parent::__construct();
        // set incoming json data
        $this->json_data = $c;
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

        try
        {

            $this->output($data);
        }
        catch(Exception $ex) {

        }

    }

    /**
     * POST
     */
    public function post($id)
    {
        //get the incoming data
        $json = $this->getJsonData();

        // make dummy data to output
        $data = [
            "controller" => "Test",
            "method" => "POST",
            "id" => $id,
            "data" => $json
        ];

        try
        {

            $this->output($data);
        }
        catch(Exception $ex) {

        }

    }

    /**
     * PUT
     */
    public function put($id)
    {
        //get the incoming data
        $json = $this->getJsonData();

        // make dummy data to output
        $data = [
            "controller" => "Test",
            "method" => "PUT",
            "id" => $id,
            "data" => $json
        ];

        try
        {

            $this->output($data);
        }
        catch(Exception $ex) {

        }


    }

    /**
     * DELETE
     */
    public function delete($id)
    {
        //get the incoming data
        $json = $this->getJsonData();

        // make dummy data to output
        $data = [
            "controller" => "Test",
            "method" => "DELETE",
            "id" => $id,
            "data" => $json
        ];

        try
        {

            $this->output($data);
        }
        catch(Exception $ex) {

        }

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


