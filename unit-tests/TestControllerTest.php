<?php

use PHPUnit\Framework\TestCase;
require_once("../config.php");
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once(CONTROLLERS_PATH . "/Test/Test.php");

class TestControllerTest extends TestCase
{
    use UtilityTest;

    protected $testObject;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange
        $inputStreamData = [
            "keyNum" => 123,
            "keyString" => "value"
        ];
        $this->testObject = new Test($inputStreamData);
    }

    /**
     * Generic Test function
     */
    protected function genericTest($method_name, $method_string)
    {
        // Act
        $data = [
            "controller" => "Test",
            "method" => "{$method_string}",
            "id" => 123,
            "data" => [
                "keyNum" => 123,
                "keyString" => "value"
            ]
        ];
        // encode the dict into json
        $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $this->testObject->{$method_name}(123);
        // get the json_string property in Authentication Class that is parent of Test controller class
        $json_string = $this->getProperty($this->testObject, "json_string");

        // Assert
        $this->assertSame($json_string, $data);
    }

    /**
     * Test GET
     */
    public function testGET()
    {
        // Act
        $data = [
            "controller" => "Test",
            "method" => "GET",
            "id" => 123
        ];
        // encode the dict into json
        $data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->testObject->get(123);
        // get the json_string property in Authentication Class that is parent of Test controller class
        $json_string = $this->getProperty($this->testObject, "json_string");

        // Assert
        $this->assertSame($json_string, $data);
    }

    /**
     * Test POST
     */
    public function testPOST()
    {
        $this->genericTest("post", "POST");
    }

    /**
     * Test PUT
     */
    public function testPUT()
    {
        $this->genericTest("put", "PUT");
    }

    /**
     * Test DELETE
     */
    public function testDELETE()
    {
        $this->genericTest("delete", "DELETE");
    }
}
