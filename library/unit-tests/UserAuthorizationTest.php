<?php

require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once (AUTHORIZATION_PATH . "/UserAuthorizedController.php");
require_once ("UtilityTest.php");
use PHPUnit\Framework\TestCase;

class UserAuthorizationTest extends TestCase
{
    private $mockFileManager;
    private $restCall;

    protected function setUp(): void
    {
        $this->mockFileManager = $this->getMockBuilder(FileManager::class)
            ->setMethods(['getHeaders'])
            ->getMock();

        $this->mockFileManager->method('getHeaders')
            ->willReturn(["Authorization" => "Bearer SomeJWTToken"]);

        $this->restCall = $this->getMockBuilder(RestCall::class)
            ->setConstructorArgs(["Curl", new FileManager])
            ->setMethods(['send', 'getResponseWithInfo'])
            ->getMock();

        $this->jsonString = "{\"scope\":[\"WRITE_VISITORS\",\"READ_VISITORS\"],\"exp\":1579384727,\"authorities\":[\"ROLE_AUTHORIZED_CLIENT\"],\"client_id\":\"some_user\"}";

        $restResponse = new RestResponse();
        $restResponse->setBody($this->jsonString)
            ->setHttpCode(200)
            ->setTime(124835, 124838);

        $this->restCall->method('getResponseWithInfo')
            ->willReturn($restResponse);
    }

    /**
     * Test for proper Authorization
     * with the right api token
     */
    public function testProperAuthorization()
    {
        $_SERVER['HTTP_APITOKEN'] = "WRCdma(&#_)*@$$@@$@#Sch38E2*$%G";

        $auth = new UserAuthorizedController();
        $result = $auth->authorize($this->mockFileManager, $this->restCall, $auth);

        $this->assertEquals(true, $result);
    }

    /**
     * Test for proper Authorization
     * with wrong api token
     */
    public function testProperAuthorizationWrongToken()
    {
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd ";

        $auth = new UserAuthorizedController();
        $result = $auth->authorize($this->mockFileManager, $this->restCall, $auth);

        $this->assertEquals(false, $result);
    }

    /**
     * Test for proper Authorization
     * with wrong api token
     */
    public function testProperAuthorizationWrongJWT()
    {
        $_SERVER['HTTP_APITOKEN'] = "WRCdma(&#_)*@$$@@$@#Sch38E2*$%G";

        $restCall = $this->getMockBuilder(RestCall::class)
            ->setConstructorArgs(["Curl", new FileManager])
            ->setMethods(['send', 'getResponseWithInfo'])
            ->getMock();

        $jsonString = "{\"message\": \"some_wrong_token_exception\"}";

        $restResponse = new RestResponse();
        $restResponse->setBody($jsonString)
            ->setHttpCode(404)
            ->setTime(124835, 124838);

        $restCall->method('getResponseWithInfo')
            ->willReturn($restResponse);

        $auth = new UserAuthorizedController();
        $result = $auth->authorize($this->mockFileManager, $restCall, $auth);

        $this->assertEquals(false, $result);
    }
}
