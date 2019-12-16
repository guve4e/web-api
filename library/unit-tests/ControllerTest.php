<?php

require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once (LIBRARY_PATH . "/controller/Controller.php");
require_once ("UtilityTest.php");

use PHPUnit\Framework\TestCase;

class ControllerTest extends TestCase
{
    use UtilityTest;

    protected $mockFile;

    protected function setUp(): void
    {
        $this->mockFile = $this->getMockBuilder(FileManager::class)
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
