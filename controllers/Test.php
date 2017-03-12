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
        // call parent first
        // to give you some functionality
        // as logging and geting data form
        // input stream
        parent::get($id);

        // dummy data
        $data = [
            "controller" => "Test",
            "method" => "GET",
            "id" => $id
        ];

        try
        {
            // send the response
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
        parent::post($id);

        // make dummy data to output
        $data = [
            "controller" => "Test",
            "method" => "POST",
            "id" => $id,
            "data" => $this->json_data
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
       parent::put($id);

        // make dummy data to output
        $data = [
            "controller" => "Test",
            "method" => "PUT",
            "id" => $id,
            "data" => $this->json_data
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
        parent::delete($id);

        // make dummy data to output
        $data = [
            "controller" => "Test",
            "method" => "DELETE",
            "id" => $id,
            "data" => $this->json_data
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


