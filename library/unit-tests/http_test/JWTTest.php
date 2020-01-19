<?php

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../../relative-paths.php";
require_once (HTTP_PATH . "/RestCall.php");
require_once (HTTP_PATH . "/JWT.php");
require_once (UTILITY_PATH . "/FileManager.php");

class JWTTest extends TestCase
{
    private $mockRestCall;
    private $jsonString;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->mockRestCall = $this->getMockBuilder(RestCall::class)
            ->setConstructorArgs(["Curl", new FileManager])
            ->setMethods(['send', 'getResponseWithInfo', 'getResponseAsJson'])
            ->getMock();

        $this->jsonString = "{" .
            "\"access_token\": \"06615fca-b806-4cd8-92f5-4de9e451769f\"," .
            "\"expires_in\": 3536," .
            "\"scope\": \"READ_ALL_GUESTS WRITE_GUEST UPDATE_GUEST\"," .
            "\"token_type\": \"bearer\"" .
            "}";

        $restResponse = new RestResponse();
        $restResponse->setBody($this->jsonString)
            ->setHttpCode(200)
            ->setTime(124835, 124838);

        $this->mockRestCall->method('getResponseWithInfo')
            ->willReturn($restResponse);

        $this->mockRestCall->method('getResponseAsJson')
            ->willReturn(json_decode($this->jsonString));
    }

    /**
     * @throws Exception
     */
    public function testAuthHeader()
    {
        $expectedString = "Bearer 06615fca-b806-4cd8-92f5-4de9e451769f";

        $info = [
            "url" => "https://some-auth.net/oauth/token",
            "username" => "some-username",
            "password" => "some-password"
        ];

        $authHeader = new JWT($this->mockRestCall, $info);
        $bearerString = $authHeader->getBearerString();

        $this->assertEquals($expectedString, $bearerString);
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderMustThrowWhenHttpCodeNot200()
    {
        $info = [
            "url" => "https://some-auth.net/oauth/token",
            "username" => "some-username",
            "password" => "some-password"
        ];

        $mockRestCall = $this->getMockBuilder(RestCall::class)
            ->setConstructorArgs(["Curl", new FileManager])
            ->setMethods(['send', 'getResponseWithInfo', 'getResponseAsJson'])
            ->getMock();

        $restResponse = new RestResponse();
        $restResponse->setBody($this->jsonString)
            ->setHttpCode(500)
            ->setTime(124835, 124838);

        $mockRestCall->method('getResponseWithInfo')
            ->willReturn($restResponse);

        $this->expectException(Exception::class);

        $authHeader = new JWT($mockRestCall, $info);
        $authHeader->getBearerString();
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderWithSetters()
    {
        $expectedString = "Bearer 06615fca-b806-4cd8-92f5-4de9e451769f";

        $authHeader = new JWT($this->mockRestCall);
        $authHeader->setUrl("https://some-auth.net/oauth/token")
            ->setUsername("some-username")
            ->setPassword("some-password");

        $bearerString = $authHeader->getBearerString();
        $this->assertEquals($expectedString, $bearerString);
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderWithSettersMustThrowSinceNoUrlIsSet()
    {
        $authHeader = new JWT($this->mockRestCall);
        $authHeader->setUsername("some-username")
            ->setPassword("some-password");

        $this->expectException(Exception::class);
        $authHeader->getBearerString();
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderWithSettersMustThrowSinceNoUserNameIsSet()
    {
        $authHeader = new JWT($this->mockRestCall);
        $authHeader->setUrl("https://some-auth.net/oauth/token")
            ->setPassword("some-password");

        $this->expectException(Exception::class);
        $authHeader->getBearerString();
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderWithSettersMustThrowSinceNoPassIsSet()
    {
        $authHeader = new JWT($this->mockRestCall);
        $authHeader->setUrl("https://some-auth.net/oauth/token")
            ->setUsername("some-username");

        $this->expectException(Exception::class);
        $authHeader->getBearerString();
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderMustThrowSinceNoUrlIsSet()
    {
        $info = [
            "username" => "some-username",
            "password" => "some-password"
        ];

        $this->expectException(Exception::class);
        $authHeader = new JWT($this->mockRestCall, $info);
        $authHeader->getBearerString();
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderMustThrowSinceNoUserNameIsSet()
    {
        $info = [
            "url" => "https://some-auth.net/oauth/token",
            "password" => "some-password"
        ];

        $this->expectException(Exception::class);
        $authHeader = new JWT($this->mockRestCall, $info);
        $authHeader->getBearerString();
    }

    /**
     * @throws Exception
     */
    public function testAuthHeaderMustThrowSinceNoPassIsSet()
    {
        $info = [
            "url" => "https://some-auth.net/oauth/token",
            "username" => "some-username"
        ];

        $this->expectException(Exception::class);
        $authHeader = new JWT($this->mockRestCall, $info);
        $authHeader->getBearerString();
    }

    /**
     * @throws Exception
     */
    public function testCheckAuthorizationToken()
    {
        $expectedResponse = [
            "scope" => [
                "READ_ALL_GUESTS",
                "WRITE_GUEST",
                "UPDATE_GUEST"
            ],
            "exp" => 1576750460,
            "authorities" => [
                "ROLE_GUESTS_AUTHORIZED_CLIENT"
            ],
            "client_id" => "guest_app"
        ];

        $mockRestCall = $this->getMockBuilder(RestCall::class)
            ->setConstructorArgs(["Curl", new FileManager])
            ->setMethods(['send', 'getResponseWithInfo', 'getResponseAsJson'])
            ->getMock();

        $restResponse = new RestResponse();
        $restResponse->setBody($this->jsonString)
            ->setHttpCode(200)
            ->setTime(124835, 124838);

        $mockRestCall->method('getResponseWithInfo')
            ->willReturn($restResponse);

        $mockRestCall->method('getResponseAsJson')
            ->willReturn(json_decode(json_encode($expectedResponse)));

        $info = [
            "url" => "https://cserum-auth-server.herokuapp.com/oauth/token",
            "username" => "guest_app",
            "password" => "secret"
        ];

        $info2 = [
            "url" => "https://cserum-auth-server.herokuapp.com/oauth/check_token",
        ];

        $jwt1 = new JWT($this->mockRestCall, $info);

        $jwt2 = new JWT($mockRestCall, $info2);
        $actual = $jwt2->checkAuthorizationToken($jwt1->getToken());

        $this->assertEquals(true, $actual);
    }
}


