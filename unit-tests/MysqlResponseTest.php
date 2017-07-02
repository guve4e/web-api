<?php

use PHPUnit\Framework\TestCase;
require_once("../config.php");
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once(LIBRARY_PATH . "/rdbms/MysqlResponse.php");

class MysqlResponseTest extends TestCase
{
    use UtilityTest;

    protected $response;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange
        $this->response = new MysqlResponse();
    }

    /**
     * Test creation of successful Response
     */
    public function testSuccessResponseCreation()
    {
        $this->response->setSuccess(true)->setExecutionTime(0.001)->setSqlQueryString("SELECT * FROM TABLE");

        $success = $this->getProperty($this->response,"success");
        $executionTime = $this->getProperty($this->response, "executionTime");
        $message = $this->getProperty($this->response, "message");
        $sql = $this->getProperty($this->response, "sqlQueryString");

        $this->assertSame($success,true);
        $this->assertSame($executionTime,0.001);
        $this->assertSame($message,"none");
        $this->assertSame($sql,"SELECT * FROM TABLE");

    }

    /**
     * Test creation of successful Response
     */
    public function testFailureResponseCreation()
    {
        $this->response->setSuccess(false)
            ->setExecutionTime(0.001)
            ->setMessage("Something bad happened")
            ->setSqlQueryString("SELECT * FROM TABLE");

        $success = $this->getProperty($this->response,"success");
        $executionTime = $this->getProperty($this->response, "executionTime");
        $message = $this->getProperty($this->response, "message");
        $sql = $this->getProperty($this->response, "sqlQueryString");

        $this->assertSame($success,false);
        $this->assertSame($executionTime,0.001);
        $this->assertSame($message,"Something bad happened");
        $this->assertSame($sql,"SELECT * FROM TABLE");
    }

    /**
     * Test the creation of the right response on Failure
     */
    public function testFailureResponse(){

        $this->response->setSuccess(false)
            ->setExecutionTime(0.001)
            ->setMessage("Something bad happened")
            ->setSqlQueryString("SELECT * FROM TABLE");

        $response = $this->response->getMySqlResponse();

        $this->assertSame($response->success,false);
        $this->assertSame($response->database_access_time,0.001);
        $this->assertSame($response->message,"Something bad happened");
    }

    /**
     * Test the creation of the right response on Failure
     */
    public function testSuccessResponse(){

        $this->response->setSuccess(true)
            ->setExecutionTime(0.001)
            ->setSqlQueryString("SELECT * FROM TABLE");

        $response = $this->response->getMySqlResponse();

        $this->assertSame($response->success,true);
        $this->assertSame($response->database_access_time,0.001);
    }

}
