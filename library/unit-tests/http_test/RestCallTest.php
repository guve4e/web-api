<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../../relative-paths.php";
require_once (HTTP_PATH . "/RestCall.php");
require_once(UTILITY_PATH . "/FileManager.php");

class RestCallTest extends TestCase
{
    private $mockConnection;

    /**
     * Create test subject before test
     */
    protected function setUp(): void
    {
        $httpResponse = "HTTP/1.1 200 OK" . "\r\n" .
                        "Date: Mon, 27 Jul 2009 12:28:53 GMT" . "\r\n" .
                        "Server: Apache/2.2.14 (Win32)" . "\r\n" .
                        "Last-Modified: Wed, 22 Jul 2009 19:15:56 GMT" . "\r\n" .
                        "Content-Length: 88" . "\r\n" .
                        "Content-Type: text/html" . "\r\n" .
                        "Connection: Closed" . "\r\n\r\n" .
                        "{ \"key\": \"value\" }";

        // Create a stub for the JsonLoader class
        $this->mockConnection = $this->getMockBuilder(FileManager::class)
            ->setMethods(array('fileExists', 'close', 'getLine', 'endOfFile', 'socket', 'write', 'jsonDecode'))
            ->getMock();

        $this->mockConnection->method('fileExists')
            ->willReturn(true);

        $this->mockConnection->method('jsonDecode')
            ->willReturn(["key" => "value", "title" => "some_title"]);

        $this->mockConnection->method('getLine')
            ->will($this->onConsecutiveCalls($httpResponse, false)); // break the loop

        $this->mockConnection->expects($this->at(2))
            ->method('endOfFile')
            ->with(null)
            ->willReturn(false);

        $this->mockConnection->expects($this->at(4))
            ->method('endOfFile')
            ->with(null)
            ->willReturn(true);
    }

    /**
     * @throws Exception
     */
    public function testSocketCall()
    {
        $restCall = new RestCall("HttpSocket", $this->mockConnection);
        $restCall->setUrl("http://webapi.ddns.net/index.php/mockcontroller/1001");
        $restCall->setContentType("application/json");
        $restCall->setMethod("POST");
        $restCall->addBodyJson(["a" => 'b']);
        $restCall->send();
        $responseAsJson = $restCall->getResponseAsJson();
        $responseAsString = $restCall->getResponseAsString();

        $this->assertEquals(["key" => "value", "title" => "some_title"], $responseAsJson);
        $this->assertEquals("{ \"key\": \"value\" }", $responseAsString);
    }

    /**
     * @throws Exception
     */
    public function testSocketCallWhenResponseWithInfo()
    {
        $restCall = new RestCall("HttpSocket", $this->mockConnection);
        $restCall->setUrl("http://webapi.ddns.net/index.php/mockcontroller/1001");
        $restCall->setContentType("application/json");
        $restCall->setMethod("POST");
        $restCall->addBodyJson(["a" => 'b']);
        $restCall->send();
        $restResponse = $restCall->getResponseWithInfo();

        $this->assertEquals(["key" => "value"], $restResponse->getBodyAsArray());
    }

    /**
     * @throws Exception
     */
    public function testCurlCallWhenBodyIsFormKeyValuePair()
    {
        $restCall = new RestCall("HttpSocket", $this->mockConnection);
        $restCall->setUrl("http://webapi.ddns.net/index.php/mockcontroller/1001");
        $restCall->setContentType("application/x-www-form-urlencoded");
        $restCall->setMethod("POST");
        $restCall->addBodyForm(["a" => 'b', 'c' => 'd', 'e' => 'f']);
        $restCall->send();
        $restResponse = $restCall->getResponseWithInfo();

        $this->assertEquals(["key" => "value"], $restResponse->getBodyAsArray());
    }
}


