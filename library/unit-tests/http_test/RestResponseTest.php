<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../../relative-paths.php";
require_once (HTTP_PATH . "/RestCall.php");
require_once (UTILITY_PATH . "/FileManager.php");

class RestResponseTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testRestResponseClassNotSuccessful()
    {
        $restResponse = new RestResponse();

        $restResponse->setBody("Some Body")
            ->setHttpCode(500)
            ->setTime(124835, 124838);

        $this->assertEquals("Some Body", $restResponse->getBodyRaw());
        $this->assertEquals(["time" => 3.0, "code"=>500, "success"=>false], $restResponse->getInfo());
        $this->assertEquals(false, $restResponse->isSuccessful());
    }

    /**
     * @throws Exception
     */
    public function testRestResponseClassSuccessful()
    {
        $restResponse = new RestResponse();

        $restResponse->setBody("Some Body")
            ->setHttpCode(200)
            ->setTime(124835, 124845);

        $this->assertEquals("Some Body", $restResponse->getBodyRaw());
        $this->assertEquals(["time" => 10.0, "code"=>200, "success"=>true], $restResponse->getInfo());
        $this->assertEquals(true, $restResponse->isSuccessful());
    }

    /**
     * @throws Exception
     */
    public function testRestResponseGetBodyAsJson()
    {
        $expectedJson = json_decode("{ \"key\": \"value\" }");

        $restResponse = new RestResponse();

        $restResponse->setBody("{ \"key\": \"value\" }")
            ->setHttpCode(200)
            ->setTime(124835, 124845);

        $this->assertEquals($expectedJson, $restResponse->getBodyAsJson());
    }

    /**
     * @throws Exception
     */
    public function testRestResponseGetBodyAsArray()
    {
        $restResponse = new RestResponse();

        $restResponse->setBody("{ \"key\": \"value\" }")
            ->setHttpCode(200)
            ->setTime(124835, 124845);

        $this->assertEquals(["key" => "value"], $restResponse->getBodyAsArray());
    }
}


