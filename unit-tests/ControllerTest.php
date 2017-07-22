<?php

require_once("../config.php");
require_once (LIBRARY_PATH . "/Controller.php");
require_once ("UtilityTest.php");
use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    use UtilityTest;

    protected $controller;

    protected $testJson;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        // Arrange

        $this->controller = new Controller();
        $this->testJson = [
          "key"=> "value"
        ];
    }

    /**
     *
     */
    public function testProperExtractionOfInput()
    {
        // Arrange
        $fileInProperty = $this->invokeProperty($this->controller,    "fileIn");
        $fileInProperty->setValue($this->controller, "test-files/input_data.dat");

        // Act
        $this->invokeMethod($this->controller,"retrieveJsonDataIn");
        $jsonDataIn = $this->controller->getJsonData();

        // Assert
        $this->assertEquals($jsonDataIn, $this->testJson);
    }
}
