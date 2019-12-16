<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once (LIBRARY_PATH . "/db/MysqlResponse.php");

class MysqlResponseTest extends TestCase
{
    use UtilityTest;

    protected $response;

    /**
     * Create test subject before test
     */
    protected function setUp(): void
    {
        // Arrange
        $this->response = new MysqlResponse();
    }

    /**
     * Test the rounding up of float
     */
    public function testRoundingUp()
    {
        // Act
        $num = $this->invokeMethod($this->response, "roundUp",[2.3333333333, 4]);

        // Assert
        $this->assertSame(2.334, $num);
    }

    /**
     * Test creation of successful Response
     */
    public function testSuccessResponseCreation()
    {
        $this->response->setSuccess(true)
            ->setExecutionTime(0.001)
            ->setData(["key" => "value"])
            ->setRowsAffected(4)
            ->setSqlQueryString("SELECT * FROM TABLE");

        $success = $this->getProperty($this->response,"success");
        $executionTime = $this->getProperty($this->response, "executionTime");
        $message = $this->getProperty($this->response, "message");
        $sql = $this->getProperty($this->response, "sqlQueryString");
        $data = $this->getProperty($this->response, "data");
        $rowsAffected = $this->getProperty($this->response, "rowsAffected");

        $this->assertSame(true, $success);
        $this->assertSame(0.001, $executionTime);
        $this->assertSame("none", $message);
        $this->assertSame("SELECT * FROM TABLE", $sql);
        $this->assertSame(["key" => "value"], $data);
        $this->assertSame(4, $rowsAffected);
    }

    /**
     * Test creation of successful Response
     */
    public function testFailureResponseCreation()
    {
        $this->response->setSuccess(false)
            ->setExecutionTime(0.001)
            ->setData(["key" => "value"])
            ->setRowsAffected(0)
            ->setMessage("Something bad happened")
            ->setSqlQueryString("SELECT * FROM TABLE");

        $success = $this->getProperty($this->response,"success");
        $executionTime = $this->getProperty($this->response, "executionTime");
        $message = $this->getProperty($this->response, "message");
        $sql = $this->getProperty($this->response, "sqlQueryString");
        $data = $this->getProperty($this->response, "data");
        $rowsAffected = $this->getProperty($this->response, "rowsAffected");

        $this->assertSame(false,$success);
        $this->assertSame(0.001, $executionTime);
        $this->assertSame("Something bad happened", $message);
        $this->assertSame("SELECT * FROM TABLE", $sql);
        $this->assertSame(["key" => "value"], $data);
        $this->assertSame(0, $rowsAffected);

    }

    /**
     * Test creation of successful Response wit Statistics
     * Instead of Boolean $result.
     * If MySQLi retrieves data from the
     * database it gives you statistics instead true/false
     * in the result field as:
     *  ex: $result = $this->service->query($sql);
     *      Here $result can contain boolean, true/false
     *      or statistics for the query.
     */
    public function testSuccessResponseCreationWitStats()
    {
        // Arrange
        $statsActual = [
            "key" => "value",
            "key2" => "value2"
        ];

        $this->response->setSuccess($statsActual)
            ->setExecutionTime(0.001)
            ->setData(["key" => "value"])
            ->setRowsAffected(4)
            ->setSqlQueryString("SELECT * FROM TABLE");

        $success = $this->getProperty($this->response,"success");
        $executionTime = $this->getProperty($this->response, "executionTime");
        $message = $this->getProperty($this->response, "message");
        $statsExpected = $this->getProperty($this->response, "stats");
        $sql = $this->getProperty($this->response, "sqlQueryString");
        $data = $this->getProperty($this->response, "data");
        $rowsAffected = $this->getProperty($this->response, "rowsAffected");

        $this->assertSame(true, $success);
        $this->assertSame(0.001, $executionTime);
        $this->assertSame("none", $message);
        $this->assertSame($statsExpected,$statsActual);
        $this->assertSame("SELECT * FROM TABLE", $sql);
        $this->assertSame(["key" => "value"], $data);
        $this->assertSame(4, $rowsAffected);

    }

    /**
     * Test the creation of the right response on Failure
     */
    public function testFailureResponse(){

        $this->response->setSuccess(false)
            ->setExecutionTime(0.001)
            ->setRowsAffected(12)
            ->setData(["key" => "value"])
            ->setMessage("Something bad happened")
            ->setSqlQueryString("SELECT * FROM TABLE");

        $response = $this->response->getMySqlResponse();

        $this->assertSame(false, $response->success);
        $this->assertSame(0.001, $response->database_access_time);
        $this->assertSame("Something bad happened", $response->message);
        $this->assertSame("none", $response->stats);
        $this->assertSame(["key" => "value"], $response->data);
        $this->assertSame(12, $response->rows_affected);
    }

    /**
     * Test the creation of the right response on Failure
     */
    public function testSuccessResponse(){

        $this->response->setSuccess(true)
            ->setExecutionTime(0.001)
            ->setRowsAffected(1)
            ->setData(["key" => "value"])
            ->setSqlQueryString("SELECT * FROM TABLE");

        $response = $this->response->getMySqlResponse();

        $this->assertSame(true, $response->success);
        $this->assertSame(0.001, $response->database_access_time);
        $this->assertSame(["key" => "value"], $response->data);
        $this->assertSame(1, $response->rows_affected);
    }

}
