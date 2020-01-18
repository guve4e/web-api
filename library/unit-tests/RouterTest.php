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

class RouterTest extends TestCase
{
    use UtilityTest;

    protected $router;
    private $mockFileManager;

    protected function setUp(): void
    {
        $_SERVER['PATH_INFO'] = "/mockcontroller/123";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_SERVER['HTTP_APITOKEN'] = "WRCdmach38E2*$%Ghdo@nf#cOBD4fd";

        $this->mockFileManager = $this->getMockBuilder(FileManager::class)
            ->setMethods(['fileExists', 'close', 'getLine', 'endOfFile', 'socket', 'write', 'jsonDecode'])
            ->getMock();

        $this->mockFileManager->method('fileExists')
            ->willReturn(true);
    }

    public function testRouterWithEmptyPathInfoExpectedException()
    {
        $this->expectException(NoSuchControllerException::class);
        new Router($this->mockFileManager, "");
    }

    public function testRouterWithControllerThatDoesNotExistExpectedException()
    {
        $this->expectException(Exception::class);
        new Router($this->mockFileManager,"/somefakecontroller/123");
    }

    /**
     * Test Build
     * @throws Exception
     */
    public function testConstruction()
    {
        new Router($this->mockFileManager, $_SERVER['PATH_INFO']);

        $this->assertTrue(class_exists("Mockcontroller22", false));
    }
}
