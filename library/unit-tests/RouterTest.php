<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 6/23/17
 * Time: 8:16 PM
 */

use PHPUnit\Framework\TestCase;
require_once dirname(__FILE__) . "/../../relative-paths.php";
require_once ("UtilityTest.php");
require_once (LIBRARY_PATH . "/Logger.php");
require_once (LIBRARY_PATH . "/Router.php");
require_once (UTILITY_PATH . "/FileManager.php");
require_once (HTTP_PATH . "/RestCall.php");

class RouterTest extends TestCase
{
    use UtilityTest;

    protected $router;
    private $mockFileManager;
    private $mockAuthorizationFilter;

    protected function setUp(): void
    {
        $_SERVER['PATH_INFO'] = "/mock-controller/123";
        $_SERVER['REQUEST_METHOD'] = "GET";

        $this->mockFileManager = $this->getMockBuilder(FileManager::class)
            ->setMethods(['fileExists', 'getParentName'])
            ->getMock();

        $this->mockFileManager->method('fileExists')
            ->willReturn(true);

        $this->mockFileManager->method('getParentName')
            ->willReturn("AuthorizedController");

        $this->mockAuthorizationFilter = $this->getMockBuilder(AuthorizationFilter::class)
            ->setConstructorArgs([$this->mockFileManager, new RestCall("Curl", $this->mockFileManager)])
            ->setMethods(['authorize'])
            ->getMock();
    }

    public function testRouterWithEmptyPathInfoExpectedException()
    {
        $this->expectException(NoSuchControllerException::class);
        new Router($this->mockFileManager, $this->mockAuthorizationFilter, "");
    }

    public function testRouterWithControllerThatDoesNotExistExpectedException()
    {
        $this->expectException(Exception::class);
        new Router($this->mockFileManager, $this->mockAuthorizationFilter,"/somefakecontroller/123");
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstructionWithUnAuthorizedController()
    {
        $mockFileManager = $this->getMockBuilder(FileManager::class)
            ->setMethods(['fileExists', 'getParentName'])
            ->getMock();

        $mockFileManager->method('fileExists')
            ->willReturn(true);

        $mockFileManager->method('getParentName')
            ->willReturn("UnAuthorizedController");

        new Router($mockFileManager, $this->mockAuthorizationFilter, $_SERVER['PATH_INFO']);

        $this->assertTrue(class_exists("MockController", false));
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstructionWithAuthorizedController()
    {
        new Router($this->mockFileManager, $this->mockAuthorizationFilter, $_SERVER['PATH_INFO']);

        $this->assertTrue(class_exists("MockController", false));
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstructionWithAuthorizedControllerWithQueryStringParameters()
    {
        $_SERVER['PATH_INFO'] = "/mock-controller/123?param1=value1&param2=value2";
        $_SERVER['REQUEST_METHOD'] = "POST";

        new Router($this->mockFileManager, $this->mockAuthorizationFilter, $_SERVER['PATH_INFO']);

        $this->assertTrue(class_exists("MockController", false));
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstructionWithUnknownController()
    {
        $mockFileManager = $this->getMockBuilder(FileManager::class)
            ->setMethods(['getParentName'])
            ->getMock();

        $this->mockFileManager->method('getParentName')
            ->willReturn("SomeOtherController");

        $this->expectException(ApiException::class);

        new Router($mockFileManager, $this->mockAuthorizationFilter, $_SERVER['PATH_INFO']);
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstructionWithNotExistingMethod()
    {
        $_SERVER['PATH_INFO'] = "/mockcontroller/123";
        $_SERVER['REQUEST_METHOD'] = "SomeNotExistingMethod";

        $this->expectException(NoSuchMethodException::class);

        new Router($this->mockFileManager, $this->mockAuthorizationFilter, $_SERVER['PATH_INFO']);
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstructionWithMethodThatTheControllerDoesntHave()
    {
        $_SERVER['PATH_INFO'] = "/mock-controller/123";
        $_SERVER['REQUEST_METHOD'] = "GET";

        $mockFileManager = $this->getMockBuilder(FileManager::class)
            ->setMethods(['methodExist'])
            ->getMock();

        $mockFileManager->method('methodExist')
            ->willReturn(false);

        $this->expectException(NoSuchMethodException::class);

        new Router($mockFileManager, $this->mockAuthorizationFilter, $_SERVER['PATH_INFO']);
    }
}
