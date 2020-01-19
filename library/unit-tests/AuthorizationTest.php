<?php

require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once(AUTHORIZATION_FILTER_PATH . "/AuthorizationFilter.php");
require_once ("UtilityTest.php");
use PHPUnit\Framework\TestCase;

class AuthorizationTest extends TestCase
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
     * @throws NotAuthorizedException
     */
    public function testProperAuthorization()
    {
        $_SERVER['HTTP_APITOKEN'] = "76E48EA91C151BFD63F51851D8C40";

        $this->expectNotToPerformAssertions();

        $auth = new AuthorizationFilter($this->mockFileManager, $this->restCall);
        $auth->authorize();
    }

    /**
     * Test for proper Authorization
     * with wrong api token
     * @throws NotAuthorizedException
     */
    public function testProperAuthorizationWrongToken()
    {
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd ";

        $this->expectException(NotAuthorizedException::class);

        $auth = new AuthorizationFilter($this->mockFileManager, $this->restCall);
        $auth->authorize();
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

        $this->expectException(NotAuthorizedException::class);

        $auth = new AuthorizationFilter($this->mockFileManager, $restCall);
        $auth->authorize();
    }
}
