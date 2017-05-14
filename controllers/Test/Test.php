<?php
require_once(AUTHENTICATION_PATH . "/UserAuthentication.php");
require_once("TestDatabase.php");

class Test extends UserAuthentication
{

    /**
     * Database Connector
     * @var null
     */
    private $db = null;

    /**
     * __construct
     *
     * @access public
     */
    public function __construct($input)
    {
        parent::__construct();
        // set incoming json data
        $this->json_data = $input;

        $this->db = new TestDatabase();
    }

    /**
     * GET
     *
     * @override
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

        try {
            // send the response
            $this->output($data);
        } catch(Exception $ex) {

        }
    }

    /**
     * POST
     *
     * @override
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

        try {
            $this->output($data);
        } catch(Exception $ex) {

        }
    }

    /**
     * PUT
     *
     * @override
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

        try {
            $this->output($data);
        } catch(Exception $ex) {

        }
    }

    /**
     * DELETE
     *
     * @override
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

        try  {
            $this->output($data);
        } catch(Exception $ex) {

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


