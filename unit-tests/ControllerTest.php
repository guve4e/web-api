<?php

require_once ("../config.php");
require_once (LIBRARY_PATH . "/Controller.php");
require_once ("UtilityTest.php");

use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    use UtilityTest;

    protected $mockFile;

    protected function setUp()
    {
        $this->mockFile = $this->getMockBuilder(File::class)
            ->setMethods(array('jsonDecode', 'loadFileContent'))
            ->getMock();

        $this->mockFile->method('loadFileContent')
            ->willReturn("{ \"key\": \"value\" }");

    }

    public function testProperExtractionOfInput()
    {
        // Arrange
        $this->expectOutputString('"{\"key\":\"value\"}"');
        $arrToSend = ["key" => "value"];
        $jsonStringToSend = json_encode($arrToSend);

        try {
            $controller = new Controller($this->mockFile);
        } catch (NoInputStreamException $e) {
            echo "Caught Exception: {$e->getMessage()}";
        } catch (ApiException $e) {
            echo "Caught Exception: {$e->getMessage()}";
        }

        $this->invokeMethod($controller, "send", [$jsonStringToSend]);
    }
}
